<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor; 
use Inertia\Inertia;

class AppointmentController extends Controller
{

    public function create(){
        $doctors = Doctor::getAffordableDoctors();
        return Inertia::render('Appointment/Create', [
            'doctors' => $doctors,
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'start_time' => 'required|date_format:Y-m-d H:i:s|before:end_time',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        if (!$doctor->is_active) {
            return response()->json(['message' => 'The doctor is no longer available.'], 409);
        }

        if ($doctor->is_on_vacation) {
            return response()->json(['message' => 'The doctor is on vacation and cannot accept appointments.'], 409);
        }

        if ($doctor->is_on_sick_leave) {
            return response()->json(['message' => 'The doctor is sick and cannot accept appointments.'], 409);
        }

        if (Appointment::hasConflict($request->doctor_id, $request->start_time, $request->end_time)) {
            return response()->json(['message' => 'The doctor is already booked for this time slot.'], 409);
        }
        
        $appointment = Appointment::create($validated);
    
        return response()->json($appointment, 201);
    }
    public function update($id, Request $request) {
        // Логіка редагування запису
    }
    public function destroy($id) {
        // Логіка видалення запису
    }
}
