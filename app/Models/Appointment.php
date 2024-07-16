<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'doctor_id', 'start_time', 'end_time'];
    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public static function hasConflict(int $doctor_id, string $start_time, string $end_time)
    {
        return self::where('doctor_id', $doctor_id)
            ->where(function($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                      ->orWhereBetween('end_time', [$start_time, $end_time])
                      ->orWhereRaw("? BETWEEN start_time AND end_time", [$start_time])
                      ->orWhereRaw("? BETWEEN start_time AND end_time", [$end_time]);
            })
            ->exists();
    }

    public static function create(){

    }
}
