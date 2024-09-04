<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor; 
use App\Models\Appointment; 
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class AppointmentController extends Controller
{

    public function create(){
        $doctors = Doctor::getAffordableDoctors();
        return Inertia::render('Appointment/Create', [
            'doctors' => $doctors,
        ]);
    }

    public function new(Request $request) {
        $checkingRequest = $this->validateRequest($request);
        Log::info('checkingRequest', $checkingRequest);

        if(!empty($checkingRequest['errors'])){
            Log::info('step 1');
            return redirect()->back()->withErrors($checkingRequest['errors']);
        }

        $checkingDoctor = $this->checkDoctor($request->doctor_id);
        if ($checkingDoctor['success'] == false) {
            Log::info('step 2');

            return redirect()->back()->withErrors(['error' => $checkingDoctor['message']]);
        }

        $checkingConflict = Appointment::hasConflict($request->doctor_id, $request->start_time, $request->end_time, $request->patient_id);
        if ($checkingConflict != null) {
            Log::info('step 3');

            return redirect()->back()->withErrors(['error' => 'The doctor is already booked for this time slot. Check appointment # ' . $checkingConflict]);
        }

        $result = Appointment::createAppointment($checkingRequest['data']);
        if ($result['success']) {
            $appointments = Appointment::get();
            Log::info('step 4');

            return Inertia::render('Appointment/List', [
                'appointments' => $appointments,
            ]);
        } else {
            Log::info('step 5');

            return redirect()->back()->withErrors(['error' => $result['error']]);
        }
    }

    public function show (int $id){
        Log::info('start');
        $doctors = Doctor::getAffordableDoctors();
        $appointment = Appointment::with('doctor')->with('patient')->find($id);
        return Inertia::render('Appointment/Create', [
            'doctors' => $doctors,
            'appointment' => $appointment,
        ]);

    }
    public function update(int $id, Request $request) {
        $checkingRequest = $this->validateRequest($request);
        if(!empty($checkingRequest['errors'])){
            return redirect()->back()->withErrors($checkingRequest['errors']);
        }

        $checkingDoctor = $this->checkDoctor($request->doctor_id);
        if ($checkingDoctor['success'] == false) {
            return redirect()->back()->withErrors(['error' => $checkingDoctor['message']]);
        }

        $checkingConflict = Appointment::hasConflict($request->doctor_id, $request->start_time, $request->end_time, $request->patient_id, $id);
        if ($checkingConflict) {
            return redirect()->back()->withErrors(['error' => 'The doctor is already booked for this time slot. Check appointment # ' . $checkingConflict]);
        }

        $result = Appointment::updateById($id, $checkingRequest['data']);
        if($result){
            $appointments = Appointment::get();
            return Inertia::render('Appointment/List', [
                'appointments' => $appointments,
            ]);
        }else{
            return redirect()->back()->withErrors(['error' => 'Can not update data']);

        }
    }
    public function destroy($id) {
        // Логіка видалення запису
    }

    public function list (){
        $appointments = Appointment::get();
        return Inertia::render('Appointment/List', [
            'appointments' => $appointments,
        ]);
    }

    public function index (int $page){
        $appointments = Appointment::get('', $page);
        echo json_encode(['data' => $appointments]);
    }

    private function validateRequest(Request $request) :array{
        $validated = '';
        $errors = '';
        try {
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'start_time' => 'required|date_format:Y-m-d\TH:i|before:end_time',
                'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
        }

        return ['data' => $validated, 'errors' => $errors];

    }

    private function checkDoctor(int $id) :array {
        $doctor = Doctor::findOrFail($id);
        
        if (!$doctor->is_active) {
            return ['success' => false, 'message' => 'The doctor is no longer available.'];
        }

        if ($doctor->is_on_vacation) {
            return ['success' => false, 'message' => 'The doctor is on vacation and cannot accept appointments.'];
        }

        if ($doctor->is_on_sick_leave) {
            return ['success' => false, 'message' => 'The doctor is sick and cannot accept appointments.'];
        }
        return ['success' => true];
    }

}
