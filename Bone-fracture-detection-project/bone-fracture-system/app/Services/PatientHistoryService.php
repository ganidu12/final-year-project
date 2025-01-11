<?php
namespace App\Services;

use App\Repository\PatientHistoryRepository;
use App\Repository\UserRepository;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class PatientHistoryService
{
    protected $patientHistoryRepository;
    protected $userRepository;

    public function __construct(PatientHistoryRepository $patientHistoryRepository, UserRepository $userRepository)
    {
        $this->patientHistoryRepository = $patientHistoryRepository;
        $this->userRepository = $userRepository;
    }
    public function saveDiagnosis($request,$imageClass,$doctor_id,$data)
    {
        try {
            $userId = $this->userRepository->findUserByEmail($request->patientEmail)->id;
            $patientHistoryData = [
                'user_id' =>$userId,
                'diagnosis' => $imageClass,
                'doctor_id' => $doctor_id,
                'image_url' => $data['image_url'],
                'fracture_size' => $data['diagonal_mm']
            ];
            return $this->patientHistoryRepository->createPatientHistory($patientHistoryData);
        }catch (\Exception $e){
            Log::info($e);
    }
    return null;
    }

    public function getPatientHistoryFromDoctorId($doctorId)
    {
        return $this->patientHistoryRepository->getPatientHistoryFromDoctorId($doctorId);
    }

    public function deletePatientHistory($id)
    {
        try {
            $this->patientHistoryRepository->deletePatientHistory($id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            throw new RuntimeException($e->getMessage());
        }
    }
}
