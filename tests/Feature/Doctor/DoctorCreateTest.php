<?php

namespace Tests\Feature\Doctor;
use App\Models\User;
use App\Models\Doctor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Inertia\Testing\AssertableInertia as Assert;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

 
class DoctorCreateTest extends TestCase{

    use RefreshDatabase;

    public function test_authenticated_user_admin_can_create_doctor(){
        $this->withoutMiddleware();
        //$this->withoutVite();
        //$this->withoutMix();
        //$this->withoutExceptionHandling();


        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
            ]);
        
        $response = $this->actingAs($user)->post('/doctors', [
            'name' => 'Dr. John Doe',
            'specialty' => 'Cardiologist',
            'is_active' => true,
            'is_on_vacation' => false,
            'is_on_sick_leave' => false

        ]);

        Log::info('response code', [$response->getStatusCode()]);


        $this->assertEquals(200, $response->getStatusCode());

        $response->assertInertia(fn (Assert $page) => 
            $page->component('Doctor/DoctorList')
                ->has('doctors')
        );

        $this->assertDatabaseHas('doctors', [
            'name' => 'Dr. John Doe'
        ]);
        

    }

    public function test_authenticated_user_cant_create_doctor(){
       
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
            ]);
        
        $response = $this->actingAs($user)->post('/doctors', [
            'name' => 'Dr. Jack Dou',
            'specialty' => 'Cardiologist',
            'is_active' => true,
            'is_on_vacation' => false,
            'is_on_sick_leave' => false

        ]);

        Log::info('status code', [$response->getStatusCode()]);


        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseMissing('doctors', [
            'name' => 'Dr. Jack Dou'
        ]);       

    }
    public function test_authenticated_user_cant_create_doctor_wrong_data(){
       
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
            ]);
        
        $response = $this->actingAs($user)->post('/doctors', [
            'name' => 'Dr. Jack Dou',
            'is_active' => true,
            'is_on_vacation' => false,
            'is_on_sick_leave' => false

        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseMissing('doctors', [
            'name' => 'Dr. Jack Dou'
        ]);       

    }
}