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
            ['firstname' => 'Patient', 'lastname' => 'One', 'phonenumber' => '11111', 'email' => 'email1@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Two', 'phonenumber' => '22222', 'email' => 'email2@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Next', 'phonenumber' => '33333', 'email' => 'email3@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Previous', 'phonenumber' => '44444', 'email' => 'email4@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Iii', 'phonenumber' => '55555', 'email' => 'email5@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Lll', 'phonenumber' => '66666', 'email' => 'email6@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Uuu', 'phonenumber' => '77777', 'email' => 'email7@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Ei', 'phonenumber' => '88888', 'email' => 'email8@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Ni', 'phonenumber' => '99999', 'email' => 'email9@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Ten', 'phonenumber' => '1010101010', 'email' => 'email10@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Tan', 'phonenumber' => '1111111111', 'email' => 'email11@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'El', 'phonenumber' => '1212121212', 'email' => 'email12@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Twe', 'phonenumber' => '1313131313', 'email' => 'email13@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Tyu', 'phonenumber' => '1414141414', 'email' => 'email14@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Fiv', 'phonenumber' => '1515151515', 'email' => 'email15@gmail.com'],
            ['firstname' => 'Patient', 'lastname' => 'Six', 'phonenumber' => '1616161616', 'email' => 'email16@gmail.com'],
        ]);
    }
}
