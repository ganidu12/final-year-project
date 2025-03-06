<?php

namespace App\Services;

use App\Mail\PatientReportMail;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailSenderService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendPatientResults($request,$imageClass,$doctorName,$data,$healingTime)
    {
        Log::info('INSIDE EMAIL FUNCTION');
        $relativePath = Str::after($data['image_url'], url('/') . '/');
        $patientData = [
            'diagnosis' => $imageClass,
            'doctorName' => $doctorName,
            'image_url' => $relativePath,
            'fracture_size' => round($data['diagonal_mm'], 2),
            'healing_time' => $healingTime
        ];
        try {
            Mail::to($request->patientEmail)->send(new PatientReportMail($patientData));
            return response()->json(['message' => 'Patient report sent successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email sending failed: ' . $e->getMessage()], 500);
        }
    }
}
