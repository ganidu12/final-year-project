<?php

namespace App\Http\Controllers;

use App\Services\PatientHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PatientHistoryController extends Controller
{
    protected $patientHistoryService;

    public function __construct(PatientHistoryService $patientHistoryService)
    {
        $this->patientHistoryService = $patientHistoryService;
    }
    public function getHistory()
    {
        $patientHistory = null;
        $user = Auth::user();
        if ($user->user_type == "doctor"){
            $patientHistory = $this->patientHistoryService->getPatientHistoryFromDoctorId($user->id);
        }
        return view('check-history', compact('patientHistory'));
    }

    public function deleteHistory(Request $request){
        $id = $request->id;
        $this->patientHistoryService->deletePatientHistory($id);

        return response()->json([
            'success' => true,
            'message' => 'Patient history deleted successfully.'
        ], 200);
    }
}
