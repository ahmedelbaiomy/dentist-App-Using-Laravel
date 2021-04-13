<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Appointment;

use Auth;


class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = DB::select('SELECT appointments.id, 
                                                patients.email AS p_email,
                                                appointments.start_time, 
                                                (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end, 
                                                appointments.comments, 
                                                appointments.status 
                                                FROM appointments
                                    LEFT JOIN users ON appointments.doctor_id = users.id
                                    LEFT JOIN patients ON patients.id = appointments.patient_id
                                    WHERE appointments.doctor_id = ?', [auth()->user()->id]);
        
        return view('doctor.appointment', compact('events'));
    }
}
