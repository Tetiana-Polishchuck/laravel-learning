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

    public static function getWithAppointmentData($id = 0){
        $currentDateTime = now();
                
        $patients = DB::table('patients')
            ->select('patients.*',
                DB::raw("(SELECT MAX(appointments.start_time) FROM appointments WHERE appointments.patient_id = patients.id AND appointments.start_time <= '$currentDateTime') AS last_visit"),
                DB::raw("(SELECT COUNT(*) FROM appointments WHERE appointments.patient_id = patients.id AND appointments.start_time <= '$currentDateTime') AS number_visits")
            )
            ->when($id === 0, function ($query) {
                return $query->orderBy('patients.id', 'desc');
            }, function ($query) use ($id) {
                return $query->where('patients.id', $id);
            })
            ->paginate(10);
        return $patients;   
    }

}
