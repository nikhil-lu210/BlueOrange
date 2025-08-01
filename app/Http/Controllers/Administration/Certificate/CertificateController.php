<?php

namespace App\Http\Controllers\Administration\Certificate;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Certificate\Certificate;
use App\Services\Administration\Certificate\CertificateService;
use App\Http\Requests\Administration\Certificate\CertificateRequest;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = $this->certificateService->getAllCertificates();
        return view('administration.certificate.index', compact(['certificates']));
    }

    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $certificates = $this->certificateService->getUserCertificates(auth()->id());
        return view('administration.certificate.my', compact(['certificates']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = $this->certificateService->getEmployeesForDropdown();
        $certificate = null; // Initialize as null for the view
        $certificateTypes = $this->certificateService->getCertificateTypes();

        return view('administration.certificate.create', compact(['employees', 'certificate', 'certificateTypes']));
    }

    /**
     * Generate certificate preview
     */
    public function generate(CertificateRequest $request)
    {
        try {
            $certificate = $this->certificateService->generateCertificatePreview($request->validated());
            $employees = $this->certificateService->getEmployeesForDropdown();
            $certificateTypes = $this->certificateService->getCertificateTypes();

            return view('administration.certificate.create', compact(['certificate', 'employees', 'certificateTypes']));
        } catch (Exception $e) {
            return back()->withError('Failed to generate certificate preview: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificateRequest $request)
    {
        try {
            $certificate = $this->certificateService->createCertificate($request->validated());

            toast('Certificate has been created successfully.', 'success');
            return redirect()->route('administration.certificate.show', $certificate);
        } catch (\Exception $e) {
            return back()->withError('Failed to create certificate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate = $this->certificateService->getCertificateWithRelations($certificate);
        return view('administration.certificate.show', compact('certificate'));
    }

    /**
     * Print the certificate
     */
    public function print(Certificate $certificate)
    {
        $certificate = $this->certificateService->getCertificateWithRelations($certificate);
        $isPrint = true;
        return view('administration.certificate.print', compact('certificate', 'isPrint'));
    }

    /**
     * Send certificate email to employee
     */
    public function sendEmail(Certificate $certificate)
    {
        try {
            $this->certificateService->sendCertificateEmail($certificate);

            toast('Certificate email sent successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            toast('Failed to send email: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        try {
            $this->certificateService->deleteCertificate($certificate);
            toast('Certificate has been deleted successfully.', 'success');
            return redirect()->route('administration.certificate.index');
        } catch (Exception $e) {
            return back()->withError('Failed to delete certificate: ' . $e->getMessage());
        }
    }

}
