<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AppointmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointments')->insert([
            ['patient_id' => 1, 'doctor_id' => 1, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 2, 'doctor_id' => 2, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 3, 'doctor_id' => 3, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 1, 'doctor_id' => 2, 'start_time' => '2024-08-01 10:00:00', 'end_time' => '2024-08-01 10:30:00'],
            ['patient_id' => 1, 'doctor_id' => 3, 'start_time' => '2024-08-01 11:00:00', 'end_time' => '2024-08-01 11:30:00'],
            ['patient_id' => 2, 'doctor_id' => 4, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 3, 'doctor_id' => 5, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 4, 'doctor_id' => 6, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 5, 'doctor_id' => 7, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 6, 'doctor_id' => 8, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 7, 'doctor_id' => 9, 'start_time' => '2024-08-01 09:00:00', 'end_time' => '2024-08-01 09:30:00'],
            ['patient_id' => 8, 'doctor_id' => 9, 'start_time' => '2024-08-06 09:00:00', 'end_time' => '2024-08-06 09:30:00'],
            ['patient_id' => 9, 'doctor_id' => 8, 'start_time' => '2024-08-02 09:00:00', 'end_time' => '2024-08-02 09:30:00'],
            ['patient_id' => 1, 'doctor_id' => 6, 'start_time' => '2024-08-02 09:00:00', 'end_time' => '2024-08-02 09:30:00'],
            ['patient_id' => 1, 'doctor_id' => 2, 'start_time' => '2024-08-03 09:00:00', 'end_time' => '2024-08-03 09:30:00'],
            ['patient_id' => 1, 'doctor_id' => 7, 'start_time' => '2024-08-04 09:00:00', 'end_time' => '2024-08-04 09:30:00'],

        ]);
    }
}
