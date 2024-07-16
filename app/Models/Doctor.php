<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'specialty', 'is_active', 'is_on_vacation', 'is_on_sick_leave'];
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    public static function getAffordableDoctors(){
        $affordableDoctors = DB::table('doctors')->where('is_active', true)
            ->where('is_on_vacation', false)
            ->where('is_on_sick_leave', false)
            ->get();
        return $affordableDoctors;
    }
}
