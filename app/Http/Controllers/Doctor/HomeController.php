<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Appointment;

use Auth;


class HomeController extends Controller
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
                                    WHERE appointments.doctor_id = ?', [auth()->user()->id]));
        return view('doctor', compact('events'));
    }
}
