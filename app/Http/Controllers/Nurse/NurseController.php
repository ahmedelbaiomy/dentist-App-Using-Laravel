<?php

namespace App\Http\Controllers\Nurse;

use Auth;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NurseController extends Controller
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
        $user_nurse_id=auth()->user()->id;
        $nurses_ids=Nurse::select('id')->where('user_id',$user_nurse_id)->pluck('id');
        $users_doctors_ids=Doctor::select('user_id')->whereIn('nurse_id',$nurses_ids)->pluck('user_id')->toArray();
        //dd($users_doctors_ids);
        $events = json_encode(DB::select('SELECT appointments.id, 
                                                patients.email AS title, 
                                                appointments.start_time AS start, 
                                                (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end, 
                                                appointments.comments AS description, 
                                                patients.id AS p_id, 
                                                (CASE WHEN patients.id % 9 = 1 THEN "new_color_1"
                                                        WHEN patients.id % 9 = 2 THEN "new_color_2"
                                                        WHEN patients.id % 9 = 3 THEN "new_color_3"
                                                        WHEN patients.id % 9 = 4 THEN "new_color_4"
                                                        WHEN patients.id % 9 = 5 THEN "new_color_5"
                                                        WHEN patients.id % 9 = 6 THEN "new_color_6"
                                                        WHEN patients.id % 9 = 7 THEN "new_color_7"
                                                        WHEN patients.id % 9 = 8 THEN "new_color_8"
                                                        WHEN patients.id % 9 = 0 THEN "new_color_9"
                                                END) AS className  FROM appointments
                                    LEFT JOIN users ON appointments.doctor_id = users.id
                                    LEFT JOIN patients ON patients.id = appointments.patient_id
                                    WHERE appointments.doctor_id IN (?)', $users_doctors_ids));
        //dd($events);                            
        return view('nurse.index', compact('events'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function appointment()
    {
        $user_nurse_id=auth()->user()->id;
        $nurses_ids=Nurse::select('id')->where('user_id',$user_nurse_id)->pluck('id');
        $users_doctors_ids=Doctor::select('user_id')->whereIn('nurse_id',$nurses_ids)->pluck('user_id')->toArray();
        $events = DB::select('SELECT appointments.id, 
                                                patients.email AS p_email,
                                                appointments.start_time, 
                                                (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end, 
                                                appointments.comments, 
                                                appointments.status,
                                                users.name as user_name 
                                                FROM appointments
                                    LEFT JOIN users ON appointments.doctor_id = users.id
                                    LEFT JOIN patients ON patients.id = appointments.patient_id
                                    WHERE appointments.doctor_id IN (?)', $users_doctors_ids);
        //dd($events);
        return view('nurse.appointment', compact('events'));
    }
}
