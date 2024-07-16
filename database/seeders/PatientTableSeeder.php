<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('patients')->insert([
            ['firstname' => 'Patient', 'lastname' => '1', 'phonenumber' => '11111', 'email' => 'email1@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '2', 'phonenumber' => '22222', 'email' => 'email2@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '3', 'phonenumber' => '33333', 'email' => 'email3@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '4', 'phonenumber' => '44444', 'email' => 'email4@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '5', 'phonenumber' => '55555', 'email' => 'email5@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '6', 'phonenumber' => '66666', 'email' => 'email6@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '7', 'phonenumber' => '77777', 'email' => 'email7@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '8', 'phonenumber' => '88888', 'email' => 'email8@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '9', 'phonenumber' => '99999', 'email' => 'email9@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '10', 'phonenumber' => '1010101010', 'email' => 'email10@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '11', 'phonenumber' => '1111111111', 'email' => 'email11@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '12', 'phonenumber' => '1212121212', 'email' => 'email12@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '13', 'phonenumber' => '1313131313', 'email' => 'email13@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '14', 'phonenumber' => '1414141414', 'email' => 'email14@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '15', 'phonenumber' => '1515151515', 'email' => 'email15@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => '16', 'phonenumber' => '1616161616', 'email' => 'email16@gmail.com'],
        ]);
    }
}
