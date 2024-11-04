<?php

namespace App\Http\Controllers\Administration\Accounts\Salary;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Mail\Administration\Accounts\PayslipMail;
use App\Services\Administration\SalaryService\SalaryService;
use App\Services\Administration\SalaryService\PayslipService;
use App\Notifications\Administration\Accounts\Salary\MonthlySalaryNotification;

class MonthlySalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch users for the filter dropdown
        $users = $this->getActiveUsers();

        // Get the filtered monthly salaries
        $monthly_salaries = $this->getFilteredMonthlySalaries($request);

        // Check if manual salary generation is allowed
        $canManuallyGenerate = $this->canManuallyGenerate();

        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        // Return the view with filtered data
        return view('administration.accounts.salary.monthly.index', compact('users', 'monthly_salaries', 'canManuallyGenerate', 'lastMonth'));
    }


    /**
     * manuallyGenerateSalary
     */
    public function manuallyGenerateSalary() 
    {
        $users = User::select('id')->whereStatus('Active')->get();
        $salaryService = new SalaryService();

        try {
            DB::transaction(function () use ($users, $salaryService) {
                foreach ($users as $user) {
                    $salaryService->calculateMonthlySalary($user);
                }
            });                
            
            toast('Monthly salaries has been generated.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(MonthlySalary $monthly_salary)
    {
        $salaryService = new SalaryService();
        $salary = $salaryService->getSalaryDetails($monthly_salary);

        $payslip = $monthly_salary->files()->first();
        
        return view('administration.accounts.salary.monthly.show', compact(['monthly_salary', 'salary', 'payslip']));
    }

    /**
     * Re-Generate Salary.
     */
    public function reGenerateSalary(MonthlySalary $monthly_salary)
    {
        if ($monthly_salary->status && $monthly_salary->status == 'Paid') {
            alert('Warning!', 'You cannot re-generate the salary which has been already marked as Paid.', 'warning');
            return redirect()->back();
        }
        $salaryService = new SalaryService();
        
        $salaryService->calculateMonthlySalary($monthly_salary->user, $monthly_salary->for_month);

        $updatedMonthlySalary = MonthlySalary::whereUserId($monthly_salary->user_id)
                                        ->whereSalaryId($monthly_salary->salary_id)
                                        ->whereForMonth($monthly_salary->for_month)
                                        ->latest()
                                        ->firstOrFail();
                                        
        toast('Salary of '.$updatedMonthlySalary->user->name.' Has Been Re-Generated Successfully.', 'success');
        return redirect()->route('administration.accounts.salary.monthly.show', ['monthly_salary' => $updatedMonthlySalary]);
    }

    /**
     * Add earning for monthly salary.
     */
    public function addEarning(Request $request, MonthlySalary $monthly_salary)
    {
        return $this->updateEarningDeductionMonthlySalary($request, $monthly_salary, 'earning');
    }

    /**
     * Add deduction for monthly salary.
     */
    public function addDeduction(Request $request, MonthlySalary $monthly_salary)
    {
        return $this->updateEarningDeductionMonthlySalary($request, $monthly_salary, 'deduction');
    }

    /**
     * Mark as "Paid" the monthly salary.
     */
    public function markAsPaid(Request $request, MonthlySalary $monthly_salary)
    {
        // dd($request->all(), $monthly_salary->toArray());
        $request->validate([
            'paid_through' => ['required', 'string', 'max:50'],
            // 'paid_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'paid_at' => ['required'],
            'payment_proof' => ['required', 'string'],
        ]);

        try {
            DB::transaction(function () use ($request, $monthly_salary) {
                $monthly_salary->update([
                    'paid_by' => auth()->user()->id,
                    'paid_through' => $request->paid_through,
                    'paid_at' => $request->paid_at,
                    'payment_proof' => $request->payment_proof,
                    'status' => 'Paid'
                    // 'status' => 'Pending'
                ]);

                $payslipService = new PayslipService();

                // Generate and Upload the Payslip
                $payslipService->generateAndUploadPayslip($monthly_salary);

                // Send Notification to System
                $monthly_salary->user->notify(new MonthlySalaryNotification($monthly_salary));

                // Send Mail to the user's email
                Mail::to($monthly_salary->user->email)->send(new PayslipMail($monthly_salary, $monthly_salary->user));
            });
            
            toast($monthly_salary->user->name . '\'s monthly salary has been paid.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * send Mail payslip
     */
    public function sendMailPayslip(MonthlySalary $monthly_salary)
    {
        // Send Mail to the user's email
        Mail::to($monthly_salary->user->email)->send(new PayslipMail($monthly_salary, $monthly_salary->user));

        toast('Payslip Mail Has Been Sent To ' . $monthly_salary->user->name, 'success');
        return redirect()->back();
    }

    

    /**
     * Update monthly salary by adding earning or deduction.
     */
    private function updateEarningDeductionMonthlySalary(Request $request, MonthlySalary $monthly_salary, string $operation)
    {
        $request->validate([
            'total' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:50'],
        ]);

        try {
            DB::transaction(function () use ($request, $monthly_salary, $operation) {
                // Determine if it’s an addition or subtraction
                $adjustment = $operation === 'earning' ? $request->total : -$request->total;
                $updatedTotalPayable = $monthly_salary->total_payable + $adjustment;

                $monthly_salary->update([
                    'total_payable' => $updatedTotalPayable
                ]);

                // Determine type label
                $type = $operation === 'earning' ? 'Plus (+)' : 'Minus (-)';

                // Create the breakdown entry
                $monthly_salary->monthly_salary_breakdowns()->create([
                    'type' => $type,
                    'reason' => $request->reason,
                    'total' => $request->total,
                ]);
            });

            // Set success message based on operation
            $message = ucfirst($operation) . ' added for ' . $monthly_salary->user->name . '\'s monthly salary.';
            toast($message, 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    protected function getActiveUsers()
    {
        return User::select(['id', 'name'])
                    ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                    ->whereStatus('Active')
                    ->get();
    }

    protected function getFilteredMonthlySalaries(Request $request)
    {
        // Get the last month (previous month) using Carbon
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        // Initialize query for MonthlySalary
        $query = MonthlySalary::with(['user', 'salary'])
            ->orderByDesc('status')
            ->orderByDesc('paid_at');

        // Apply filters based on request inputs
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('for_month')) {
            try {
                // Convert "September 2024" to "2024-09"
                $formattedMonth = Carbon::parse($request->for_month)->format('Y-m');
                $query->where('for_month', $formattedMonth);
            } catch (Exception $e) {
                toast('Invalid date format for for_month.', 'error');
                return redirect()->back()->withInput();
            }
        } else {
            // Default to last month if no 'for_month' filter is provided
            $query->where('for_month', $lastMonth);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Fetch filtered MonthlySalary records
        return $query->get();
    }

    protected function canManuallyGenerate()
    {
        // Get current time and the first day of the current month at 09:00 AM
        $now = Carbon::now();
        $firstDayOfCurrentMonth = Carbon::now()->firstOfMonth();
        $thresholdTime = $firstDayOfCurrentMonth->copy()->addHours(9);

        // Get the last month (previous month)
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        // Check if there are any MonthlySalary records for the last month
        $hasLastMonthSalaries = MonthlySalary::where('for_month', $lastMonth)->exists();

        // Determine if manual generation can be done
        return !$hasLastMonthSalaries && $now->greaterThan($thresholdTime);
    }

}
