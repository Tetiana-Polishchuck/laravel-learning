<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DoctorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('doctors')->insert([
            ['name' => 'Doctor 1', 'specialty' => 'Neurosurgeon'],
            ['name' => 'Doctor 2', 'specialty' => 'Therapist'],
            ['name' => 'Doctor 3', 'specialty' => 'Dentist'],
            ['name' => 'Doctor 4', 'specialty' => 'Neurosurgeon'],
            ['name' => 'Doctor 5', 'specialty' => 'Therapist'],
            ['name' => 'Doctor 6', 'specialty' => 'Dentist'],
            ['name' => 'Doctor 7', 'specialty' => 'Neurosurgeon'],
            ['name' => 'Doctor 8', 'specialty' => 'Therapist'],
            ['name' => 'Doctor 9', 'specialty' => 'Dentist'],
        ]);
    }
}
