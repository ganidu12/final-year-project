<?php
namespace App\Repository;

use App\Http\Controllers\PatientHistoryController;
use App\Models\PatientHistory;
use App\Services\PatientHistoryService;
use Carbon\Carbon;
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

    public function getPatientHistoryFromDoctorId($doctorId)
    {
        $patientHistory = PatientHistory::where('doctor_id',$doctorId)->where('deleted_at',null)->with('user')->get();
        return $patientHistory;
    }

    public function deletePatientHistory($id)
    {
        $deletedPatientHistory = PatientHistory::where('id',$id)->update(['deleted_at' => Carbon::now()]);
        return $deletedPatientHistory;
    }

    public function addFeedback($doctor_id, $feedback)
    {
        $updatedPatientHistory = PatientHistory::where('doctor_id', $doctor_id)
            ->orderBy('created_at', 'desc')
            ->first();

        $updatedPatientHistory->update(['feedback' => $feedback]);
        return $updatedPatientHistory;
    }

}
