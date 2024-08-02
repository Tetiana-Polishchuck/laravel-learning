<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
use Inertia\Inertia;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function store(Request $request) {
        $checkRequest = $this->validateRequest(request());
        if(!empty($checkingRequest['errors'])){
            return redirect()->back()->withErrors($checkRequest['errors']);
        }
        try {
            Doctor::create($checkRequest['data']);            
            $doctors = Doctor::getWithAppointmentData();
            return Inertia::render('Doctor/DoctorList', [
                'doctors' => $doctors,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
       
    }
    public function destroy($id) {
        // Логіка видалення лікаря
    }

    public function show(){

    }

    public function index(){
        $doctors = Doctor::getWithAppointmentData();
        return Inertia::render('Doctor/DoctorList', [
            'doctors' => $doctors,
        ]);
    }

    public function create(){
        return Inertia::render('Doctor/CreateDoctor', []);
    }

    private function validateRequest(Request $request) :array{
        $validated = '';
        $errors = '';
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'doctor_id' => 'required|string|max:255',
                'is_active' => 'required|boolean',
                'is_on_vacation' => 'required|boolean',
                'is_on_sick_leave' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
        }

        return ['data' => $validated, 'errors' => $errors];

    }

    
    

}
