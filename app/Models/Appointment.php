<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    public $table = 'appointments';

    protected $fillable = ['patient_id', 'doctor_id', 'start_time', 'duration', 'comments', 'status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id');
    }
}
