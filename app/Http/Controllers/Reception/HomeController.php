<?php

namespace App\Http\Controllers\Reception;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Appointment;


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


        // $events = json_encode(DB::select('SELECT appointments.id, 
        //                                         CONCAT("P : ",patients.email, " -- D : ", users.email) AS title, 
        //                                         appointments.start_time AS start, 
        //                                         (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end, 
        //                                         (CASE WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 7 THEN CONCAT("#", CONV(16712448/appointments.id, 10, 16))
        //                                                 WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 6 THEN CONCAT(CONCAT("#", CONV(16712448/appointments.id, 10, 16)), "0")
        //                                                 WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 5 THEN CONCAT(CONCAT("#", CONV(16712448/appointments.id, 10, 16)), "00")
        //                                                 WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 4 THEN CONCAT(CONCAT("#", CONV(16712448/appointments.id, 10, 16)), "000")
        //                                                 WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 3 THEN CONCAT(CONCAT("#", CONV(16712448/appointments.id, 10, 16)), "0000")
        //                                                 WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 2 THEN CONCAT(CONCAT("#", CONV(16712448/appointments.id, 10, 16)), "00000")
        //                                                 WHEN LENGTH(CONCAT("#", CONV(16712448/appointments.id, 10, 16))) = 1 THEN CONCAT(CONCAT("#", CONV(16712448/appointments.id, 10, 16)), "000000")
        //                                         END) AS color,
        //                                         appointments.comments AS description 
        //                                         FROM appointments
        //                                 LEFT JOIN users ON appointments.doctor_id = users.id
        //                                 LEFT JOIN patients ON patients.id = appointments.patient_id'));

        $events = collect(DB::select('SELECT appointments.id, 
                                                CONCAT(" ", users.name) AS title, 
                                                appointments.start_time AS start, 
                                                (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end, 
                                                (CASE WHEN users.id % 9 = 1 THEN "new_color_1"
                                                        WHEN users.id % 9 = 2 THEN "new_color_2"
                                                        WHEN users.id % 9 = 3 THEN "new_color_3"
                                                        WHEN users.id % 9 = 4 THEN "new_color_4"
                                                        WHEN users.id % 9 = 5 THEN "new_color_5"
                                                        WHEN users.id % 9 = 6 THEN "new_color_6"
                                                        WHEN users.id % 9 = 7 THEN "new_color_7"
                                                        WHEN users.id % 9 = 8 THEN "new_color_8"
                                                        WHEN users.id % 9 = 0 THEN "new_color_9"
                                                END) AS className,
                                                appointments.comments AS description ,
                                                patients.id AS p_id,
                                                users.id AS d_id 
                                                FROM appointments
                                        LEFT JOIN users ON appointments.doctor_id = users.id
                                        LEFT JOIN patients ON patients.id = appointments.patient_id'))->unique('d_id');
        return view('reception', compact('events'));
    }

    public function getProfile($id) 
    {
        $patient_id = $id;

        $services = DB::select("SELECT patient_notes.id, patient_notes.patient_id, patient_notes.teeth_id, patient_notes.note, patient_notes.category_id, service_categories.name, services.price FROM patient_notes 
                                    LEFT JOIN service_categories on patient_notes.category_id = service_categories.id
                                    left join services on service_categories.id = services.category_id
                                    WHERE patient_notes.patient_id = ?", [$id]);
       // $services = json_encode($service);

        $data = DB::select("SELECT * FROM service_categories");
        $cat_arrs = array();
        foreach($data as $row) { 
            $cat_arrs[] = array(
                "id" => $row ->id,
                "parent" => $row -> parent_id,
                "text" => $row -> name
            );
        }
        $datas = $cat_arrs;

        return response()->json(['state'=>true, 'services'=> $services, 'datas' => $datas]);
    }

    public function getDoctorappointmentCalender(Request $request){
        $events     = collect(DB::select('SELECT appointments.id, 
                                                CONCAT(" ", patients.name) AS title, 
                                                appointments.start_time AS start, 
                                                (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end, 
                                                (CASE WHEN users.id % 9 = 1 THEN "new_color_1"
                                                        WHEN users.id % 9 = 2 THEN "new_color_2"
                                                        WHEN users.id % 9 = 3 THEN "new_color_3"
                                                        WHEN users.id % 9 = 4 THEN "new_color_4"
                                                        WHEN users.id % 9 = 5 THEN "new_color_5"
                                                        WHEN users.id % 9 = 6 THEN "new_color_6"
                                                        WHEN users.id % 9 = 7 THEN "new_color_7"
                                                        WHEN users.id % 9 = 8 THEN "new_color_8"
                                                        WHEN users.id % 9 = 0 THEN "new_color_9"
                                                END) AS className,
                                                appointments.comments AS description ,
                                                patients.id AS p_id,
                                                users.id AS d_id 
                                                FROM appointments
                                        LEFT JOIN users ON appointments.doctor_id = users.id
                                        LEFT JOIN patients ON patients.id = appointments.patient_id'))->whereIn('d_id',$request->doctor_id);

                                    return response()->json($events);

    }
}
