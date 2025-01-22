<?php

namespace App\Services\Administration\SalaryService;

use Illuminate\Http\UploadedFile;
use App\Models\FileMedia\FileMedia;
use Spatie\Browsershot\Browsershot;
use App\Models\Salary\Monthly\MonthlySalary;

class PayslipService
{
    /**
     * Generate and upload a payslip for the given monthly salary.
     *
     * @param MonthlySalary $monthly_salary
     * @return FileMedia
     */
    public function generateAndUploadPayslip(MonthlySalary $monthly_salary): FileMedia
    {
        // Generate the PDF content
        $pdfContent = $this->generatePayslip($monthly_salary);

        // Upload the generated PDF and return the FileMedia object
        return $this->uploadPayslip($pdfContent, $monthly_salary);
    }


    /**
     * Generate a PDF from the payslip view.
     *
     * @param MonthlySalary $monthly_salary
     * @return string PDF content
     */
    private function generatePayslip(MonthlySalary $monthly_salary): string
    {
        $payslipId = $monthly_salary->payslip_id;
        $userId = $monthly_salary->user->userid;
        $id = encrypt($monthly_salary->id);

        $url = route('application.accounts.salary.monthly.show', ['payslip_id' => $payslipId, 'userid' => $userId, 'id' => $id]);

        $nodeBinaryPath = config('browsershot.binary_path');
        $nodeMemorySize = config('browsershot.memory_size');

        return Browsershot::url($url)
            ->format('A3')
            ->setNodeBinary($nodeBinaryPath)
            ->setOption('args', ["--max-old-space-size={$nodeMemorySize}"])
            ->showBackground()
            ->pdf();
    }

    /**
     * Store the generated payslip as a file and associate it with the model.
     *
     * @param string $pdfContent
     * @param MonthlySalary $monthly_salary
     * @return FileMedia
     */
    private function uploadPayslip(string $pdfContent, MonthlySalary $monthly_salary): FileMedia
    {
        $userId = $monthly_salary->user->userid; // Get the uploader's user ID
        $payslipId = $monthly_salary->payslip_id; // Get the monthly salary ID
        $directory = "payslips/{$userId}/{$payslipId}"; // Define the directory for the user
        $fileName = "{$payslipId}.pdf"; // Name the PDF file with the payslip ID
        $tempFilePath = tempnam(sys_get_temp_dir(), 'payslip_') . '.pdf'; // Create a temporary file
        file_put_contents($tempFilePath, $pdfContent); // Save the PDF content to the temp file

        // Create a new UploadedFile instance with the correct name and path
        $uploadedFile = new UploadedFile(
            $tempFilePath,     // Path to the temporary file
            $fileName,         // Desired name for the uploaded file
            'application/pdf', // Mime type of the file
            null,              // Use null for the error (optional)
            true               // Set to true to indicate this is a test file
        );

        // Use the store_file_media function to upload the PDF
        $fileMedia = store_file_media($uploadedFile, $monthly_salary, $directory);

        // Delete the temporary file
        unlink($tempFilePath);

        return $fileMedia;
    }

}
