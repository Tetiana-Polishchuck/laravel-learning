<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Inertia\Inertia;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request) {
        // Логіка додавання пацієнта
    }
    public function destroy($id) {
        // Логіка видалення пацієнта
    }
    
    public function search(Request $request)
    {
        $query = $request->get('query');
        $patients = Patient::search($query);
        return response()->json($patients);
    }

    public function all(){
        $patients = Patient::getWithAppointmentData();
        return Inertia::render('Patient/PatientList', [
            'patients' => $patients,
        ]);
    }

    public function patient(int $id)
    {
        $patient = Patient::find($id);        
        if ($patient) {
            return response()->json($patient);
        }
        return response()->json([]);
}
}
