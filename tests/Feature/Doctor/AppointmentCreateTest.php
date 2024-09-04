<?php

namespace Tests\Feature\Doctor;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Inertia\Testing\AssertableInertia as Assert;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

 
class AppointmentCreateTest extends TestCase{

    use RefreshDatabase;

    public function test_authenticated_user_can_create_appointment(){
        //$this->withoutMiddleware();
        //$this->withoutVite();
        //$this->withoutMix();
        //$this->withoutExceptionHandling();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
            ]);
        
        $response_doctor = $this->actingAs($user)->post('/doctors', [
            'name' => 'Dr. John Doe',
            'specialty' => 'Cardiologist',
            'is_active' => true,
            'is_on_vacation' => false,
            'is_on_sick_leave' => false
        ]);

        $doctor = Doctor::where('name', 'Dr. John Doe')->first();
        $doctorId = $doctor->id;

        $response_patient = $this->actingAs($user)->post('/patients/new', [
            'firstname' => 'test',
            'lastname' => 'patiemt',
            'email' => 'test@email.com',
            'phonenumber' => '216 245-2368',
        ]);
        $patient = Patient::where('phonenumber', '216 245-2368')->first();
        $patientId = $patient->id;

        $response = $this->actingAs($user)->post('/appointments/new', [
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'start_time' => '2025-01-30T19:10',
            'end_time' => '2025-01-30T19:15',
        ]);


        Log::info('AppointmentCreateTest code', [$response->getStatusCode()]);


        $this->assertEquals(200, $response->getStatusCode());

        $response->assertInertia(fn (Assert $page) => 
            $page->component('Appointment/All')
                ->has('appointments')
        );

        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctorId,
            'patient_id' => $patientId
        ]);
        

    }
    public function test_authenticated_user_cant_create_appointment_without_doctor(){
    
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
            ]);
        
        
        $response_patient = $this->actingAs($user)->post('/patients/new', [
            'firstname' => 'test',
            'lastname' => 'patiemt',
            'email' => 'test@email.com',
            'phonenumber' => '216 245-2368',
        ]);
        $patient = Patient::where('phonenumber', '216 245-2368')->first();
        $patientId = $patient->id;

        $response = $this->actingAs($user)->post('/appointments/new', [
            'patient_id' => $patientId,
            'doctor_id' => 30000,
            'start_time' => '2025-01-30T19:10',
            'end_time' => '2025-01-30T19:15',
        ]);

        $response->assertSessionHasErrors(['doctor_id']);

        $response->assertRedirect();


        $this->assertDatabaseMissing('appointments', [
            'doctor_id' => 30000,
            'patient_id' => $patientId
        ]);
        

    }
    public function test_authenticated_user_cant_create_appointment_without_patient(){
        
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
            ]);
        
        
        $response_doctor = $this->actingAs($user)->post('/doctors', [
            'name' => 'Dr. John Doe',
            'specialty' => 'Cardiologist',
            'is_active' => true,
            'is_on_vacation' => false,
            'is_on_sick_leave' => false
        ]);

        $doctor = Doctor::where('name', 'Dr. John Doe')->first();
        $doctorId = $doctor->id;

        $response = $this->actingAs($user)->post('/appointments/new', [
            'patient_id' => 30000,
            'doctor_id' => $doctorId,
            'start_time' => '2025-01-30T19:10',
            'end_time' => '2025-01-30T19:15',
        ]);

        $response->assertSessionHasErrors(['patient_id']);

        $response->assertRedirect();


        $this->assertDatabaseMissing('appointments', [
            'doctor_id' => $doctorId,
            'patient_id' => 30000
        ]);       
    }
    public function test_authenticated_user_cant_create_appointment_wrong_time(){
        
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
            ]);
        
        
        $response_doctor = $this->actingAs($user)->post('/doctors', [
            'name' => 'Dr. John Doe',
            'specialty' => 'Cardiologist',
            'is_active' => true,
            'is_on_vacation' => false,
            'is_on_sick_leave' => false
        ]);

        $doctor = Doctor::where('name', 'Dr. John Doe')->first();
        $doctorId = $doctor->id;

        $response_patient = $this->actingAs($user)->post('/patients/new', [
            'firstname' => 'test',
            'lastname' => 'patiemt',
            'email' => 'test@email.com',
            'phonenumber' => '216 245-2368',
        ]);
        $patient = Patient::where('phonenumber', '216 245-2368')->first();
        $patientId = $patient->id;

        $response = $this->actingAs($user)->post('/appointments/new', [
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'start_time' => '2025-01-30T19:10',
            'end_time' => '2025-01-10T19:10',
        ]);

        $response->assertSessionHasErrors(['start_time']);

        $response->assertRedirect();


        $this->assertDatabaseMissing('appointments', [
            'doctor_id' => $doctorId,
            'patient_id' => $patientId
        ]);
        

    }

}