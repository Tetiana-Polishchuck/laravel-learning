<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['firstname', 'lastname', 'phonenumber', 'email'];


    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    public static function search (string $query) :object{
        return DB::table('patients')->where('firstname', 'like', "%{$query}%")
            ->orWhere('lastname', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phonenumber', 'like', "%{$query}%")
            ->get();
    }
}
