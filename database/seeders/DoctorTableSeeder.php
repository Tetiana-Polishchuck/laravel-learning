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
            ['name' => 'Doctor 1', 'specialty' => 'Neurosurgeon', 'phone' => '545-54-54', 'email' => 'firstdoctor@gmail.com'],
            ['name' => 'Doctor 2', 'specialty' => 'Therapist', 'phone' => '774-74-74', 'email' => 'seconddoctor@gmail.com'],
            ['name' => 'Doctor 3', 'specialty' => 'Dentist', 'phone' => '344-34-34', 'email' => 'dentistdoctor@gmail.com'],
            ['name' => 'Doctor 4', 'specialty' => 'Neurosurgeon', 'phone' => '223-43-22', 'email' => 'fdoctor@gmail.com'],
            ['name' => 'Doctor 5', 'specialty' => 'Therapist', 'phone' => '224-54-66', 'email' => 'fidoctor@gmail.com'],
            ['name' => 'Doctor 6', 'specialty' => 'Dentist', 'phone' => '666-54-54', 'email' => 'sixtdoctor@gmail.com'],
            ['name' => 'Doctor 7', 'specialty' => 'Neurosurgeon', 'phone' => '777-54-54', 'email' => 'seventdoctor@gmail.com'],
            ['name' => 'Doctor 8', 'specialty' => 'Therapist', 'phone' => '999-54-54', 'email' => 'eidoctor@gmail.com'],
            ['name' => 'Doctor 9', 'specialty' => 'Dentist', 'phone' => '909-54-54', 'email' => 'ninetdoctor@gmail.com'],
        ]);
    }
}
