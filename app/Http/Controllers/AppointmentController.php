<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor; 
use App\Models\Appointment; 
use Inertia\Inertia;

class AppointmentController extends Controller
{

    public function create(){
        $doctors = Doctor::getAffordableDoctors();
        return Inertia::render('Appointment/Create', [
            'doctors' => $doctors,
        ]);
    }

    public function new(Request $request) {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'start_time' => 'required|date_format:Y-m-d\TH:i|before:end_time',
                'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $doctor = Doctor::findOrFail($request->doctor_id);

        if (!$doctor->is_active) {
            return redirect()->back()->withErrors(['doctor' => 'The doctor is no longer available.']);
        }

        if ($doctor->is_on_vacation) {
            return redirect()->back()->withErrors(['doctor' => 'The doctor is on vacation and cannot accept appointments.']);
        }

        if ($doctor->is_on_sick_leave) {
            return redirect()->back()->withErrors(['doctor' => 'The doctor is sick and cannot accept appointments.']);
        }

        if (Appointment::hasConflict($request->doctor_id, $request->start_time, $request->end_time)) {
            return redirect()->back()->withErrors(['appointment' => 'The doctor is already booked for this time slot.']);
        }
        $result = Appointment::createAppointment($validated);

        if ($result['success']) {
            $appointments = Appointment::get();
            return Inertia::render('Appointment/All', [
                'appointments' => $appointments,
            ]);
        } else {
            return redirect()->back()->withErrors(['appointment' => $result['error']]);
        }
    }
    public function update($id, Request $request) {
        // Логіка редагування запису
    }
    public function destroy($id) {
        // Логіка видалення запису
    }

    public function appointments (){
        $appointments = Appointment::get();
        return Inertia::render('Appointment/All', [
            'appointments' => $appointments,
        ]);
    }

    public function show($id) {
        $appointment = Appointment::get($id);
        return Inertia::render('Appointments/Show', ['appointment' => $appointment]);
    }
}
