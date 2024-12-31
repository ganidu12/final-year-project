<?php
namespace App\Services;

use App\Repository\PatientHistoryRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PatientHistoryService
{
    protected $patientHistoryRepository;
    protected $userRepository;

    public function __construct(PatientHistoryRepository $patientHistoryRepository, UserRepository $userRepository)
    {
        $this->patientHistoryRepository = $patientHistoryRepository;
        $this->userRepository = $userRepository;
    }
    public function saveDiagnosis($request,$data,$imageClass,$doctor_id)
    {
        try {
            $userId = $this->userRepository->findUserByEmail($request->patientEmail)->id;
            $patientHistoryData = [
                'user_id' =>$userId,
                'diagnosis' => $imageClass,
                'image_url' => $data['image_url'],
                'doctor_id' => $doctor_id,
                'fracture_size' => $data['diagonal_mm']
            ];
            return $this->patientHistoryRepository->createPatientHistory($patientHistoryData);
        }catch (\Exception $e){
            Log::info($e);
    }
    Log::info("NULLL");
    return null;
    }
}
