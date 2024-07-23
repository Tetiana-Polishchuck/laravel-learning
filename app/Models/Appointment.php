<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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

    public static function createAppointment(array $data) :array {
        DB::beginTransaction();
        try {      
            $appointment = self::create($data);
            DB::commit();
            return ['success' => true, 'appointment' => $appointment];

        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1062) { // 1062 - код помилки для дубліката унікального ключа
                return ['success' => false, 'error' => 'Doctor is already booked for this time'];
            }
            return ['success' => false, 'error' => 'An error occurred while creating the appointment'];
        }
    }

    public static function get(int $id = 0){
        $appointments = DB::table('appointments')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->select('appointments.id as appointment_id', 'appointments.start_time', 'appointments.end_time', 'doctors.name as doctor_name', 'doctors.specialty as doctor_specialty', 'patients.*')
            ->when($id === 0, function ($query) {
                return $query->orderBy('appointments.id', 'desc');
            }, function ($query) use ($id) {
                return $query->where('appointments.id', $id);
            })
            ->paginate(10);
        return $appointments;       
    }
}
