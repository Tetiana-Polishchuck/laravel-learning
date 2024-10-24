<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class DoctorController extends Controller
{
    public function store(Request $request) {
        $checkRequest = $this->validateRequest($request);
        if(!empty($checkRequest['errors'])){
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

    public function show(int $id){
        Log::info('show', [$id]);
        $doctor = Doctor::find($id);
        return Inertia::render('Doctor/CreateDoctor', [
            'doctor' => $doctor
        ]);
    }

    public function list(){
        $doctors = Doctor::getWithAppointmentData();
        return Inertia::render('Doctor/DoctorList', [
            'doctors' => $doctors,
        ]);
    }

    public function create(){
        return Inertia::render('Doctor/CreateDoctor', []);
    }

    public function update (int $id, Request $request){
        $checkingRequest = $this->validateRequest($request);
        if(!empty($checkingRequest['errors'])){
            return redirect()->back()->withErrors($checkingRequest['errors']);
        }

        $result = Doctor::updateById($id, $checkingRequest['data']);
        if($result){
            $doctors = Doctor::getWithAppointmentData();
            return Inertia::render('Doctor/DoctorList', [
                'doctors' => $doctors,
            ]);
        }else{
            return redirect()->back()->withErrors(['error' => 'Can not update data']);

        }
    }

    private function validateRequest(Request $request) :array{
        $validated = '';
        $errors = '';
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'specialty' => 'required|string|max:255',
                'phone' => 'required|string|max:30',
                'email' => 'required|string|max:254',
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
