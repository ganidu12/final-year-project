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
    public function saveDiagnosis($request,$imageClass,$doctor_id,$data,$healingTime,$isNotRegistered)
    {
//        try {
//            if ($isNotRegistered){
//                $patientHistoryData = [
//                    'diagnosis' => $imageClass,
//                    'doctor_id' => $doctor_id,
//                    'image_url' => $data['image_url'],
//                    'fracture_size' => round($data['diagonal_mm'], 2),
//                    'healing_time' => $healingTime,
//                    'patient_name' => $request->patientName,
//                    'patient_email' => $request->patientEmail
//                ];
//            }else{
//                $userId = $this->userRepository->findUserByEmail($request->patientEmail)->id;
//                $patientHistoryData = [
//                    'user_id' =>$userId,
//                    'diagnosis' => $imageClass,
//                    'doctor_id' => $doctor_id,
//                    'image_url' => $data['image_url'],
//                    'fracture_size' => round($data['diagonal_mm'], 2),
//                    'healing_time' => $healingTime
//                ];
//            }
//
//            return $this->patientHistoryRepository->createPatientHistory($patientHistoryData);
//        }catch (\Exception $e){
//            Log::info($e);
//    }
//    return null;
        try {
            $userId = !$isNotRegistered ? $this->userRepository->findUserByEmail($request->patientEmail)->id : null;
            Log::info("USER ID ".$userId);
            $patientHistoryData = [
                'user_id' => $userId,
                'patient_name' => $isNotRegistered ? $request->patientName : null,
                'patient_email' => $isNotRegistered ? $request->patientEmail : null,
                'diagnosis' => $imageClass,
                'doctor_id' => $doctor_id,
                'image_url' => $data['image_url'],
                'fracture_size' => round($data['diagonal_mm'], 2),
                'healing_time' => $healingTime
            ];

            return $this->patientHistoryRepository->createPatientHistory($patientHistoryData);
        } catch (\Exception $e) {
            Log::error("Error saving patient history: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save patient history'], 500);
        }

    }

    public function getPatientHistoryFromDoctorId($doctorId)
    {
        return $this->patientHistoryRepository->getPatientHistoryFromDoctorId($doctorId);
    }

    public function getPatientHistoryFromUserId($userId)
    {
        return $this->patientHistoryRepository->getPatientHistoryFromUserId($userId);
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

    public function addFeedback($feedback)
    {
        $doctor_id = Auth::user()->id;
        return $this->patientHistoryRepository->addFeedback($doctor_id,$feedback);
    }
}
