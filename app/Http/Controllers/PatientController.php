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
    
    public function create(){
        return Inertia::render('Patient/CreatePatient', []);
    }

    public function new(Request $request){
        try {
            $validated = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'phonenumber' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
        $result = Patient::create($validated);

        return redirect()->route('patients.all')->with('success', "Пацієнт {$result->firstname} {$result->lastname} створений успішно.");


    }
}
