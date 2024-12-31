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
    public function saveDiagnosis($request,$imageClass,$doctor_id,$data = null)
    {
        try {
            $userId = $this->userRepository->findUserByEmail($request->patientEmail)->id;
            $patientHistoryData = [
                'user_id' =>$userId,
                'diagnosis' => $imageClass,
                'doctor_id' => $doctor_id
            ];
            if ($data) {
                $patientHistoryData['image_url'] = $data['image_url'];
                $patientHistoryData['fracture_size'] = $data['diagonal_mm'];
            }
            return $this->patientHistoryRepository->createPatientHistory($patientHistoryData);
        }catch (\Exception $e){
            Log::info($e);
    }
    Log::info("NULLL");
    return null;
    }
}
