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

    public static function getWithAppointmentData($id = 0){
        $currentDateTime = now();
                
        $doctors = DB::table('doctors')
            ->select('doctors.*',
                DB::raw("(SELECT COUNT(*) FROM appointments WHERE appointments.doctor_id = doctors.id AND appointments.start_time > '$currentDateTime') AS planed_visits"),
                DB::raw("(SELECT COUNT(*) FROM appointments WHERE appointments.doctor_id = doctors.id AND appointments.start_time <= '$currentDateTime') AS done_visits")
            )
            ->when($id === 0, function ($query) {
                return $query->orderBy('doctors.id', 'desc');
            }, function ($query) use ($id) {
                return $query->where('doctors.id', $id);
            })
            ->paginate(10);
        return $doctors;   
    }

}
