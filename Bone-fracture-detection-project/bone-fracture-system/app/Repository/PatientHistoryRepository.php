<?php
namespace App\Repository;

use App\Models\PatientHistory;
use App\Services\PatientHistoryService;
use Illuminate\Support\Str;

class PatientHistoryRepository
{
    public function createPatientHistory(array $data)
    {
        $patientHistory = new PatientHistory;
        $patientHistory->id = (string) Str::uuid();
        $patientHistory->user_id = $data['user_id'];
        $patientHistory->diagnosis = $data['diagnosis'];
        $patientHistory->fracture_size = $data['fracture_size'];
        $patientHistory->image_url = $data['image_url'];
        $patientHistory->doctor_id = $data['doctor_id'];
        $patientHistory->save();
        return $patientHistory;
    }
}
