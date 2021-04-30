<?php

namespace App\Http\Controllers\Reception;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Library\Services\DbHelperTools;


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
                                    $newData=[];
                                    if(count($events)>0){
                                        foreach($events as $e){
                                            $newData[]=$e;
                                        }
                                    }
                                    return response()->json($newData);

    }
    public function getDoctorNearstTime($doctor_id,$start_date){
        $DbHelperTools=new DbHelperTools();
        $rs=$DbHelperTools->checkNearstAvalabilityTime($doctor_id,$start_date,0);
        return response()->json($rs);
    }
    public function getDoctorTimeSlots($doctor_id,$start_date){
        //Carbon::createFromFormat('Y-m-d H:i:s',$start_date)->dayOfWeek;
        //$number_day_of_week = $users = DB::table('doctor_schedules')->select('id','start_hour')->whereRaw(DB::raw('WEEKDAY(start_hour) = '.$number_day_of_week))->get();
        $today=Carbon::now();  
        $bookedSlots = [];
        $rsBookedSlots=Appointment::where('doctor_id',$doctor_id)->where('start_time','LIKE','%'.$start_date.'%')->pluck('start_time')->toArray();
        if(count($rsBookedSlots)>0){
            foreach($rsBookedSlots as $date){
                $dt=Carbon::createFromFormat('Y-m-d H:i:s',$date);
                $bookedSlots[]=$dt->format('H:i');
            }
        }
        //dd($bookedSlots);
        $day = strtoupper(Carbon::createFromFormat('Y-m-d',$start_date)->format('l'));
        //dd($day);
        $slots = null;
        if($doctor_id>0 && $day!=''){
            $slots = Schedule::where([['doctor_id',$doctor_id],['day',$day]])->orderBy('slot')->get();
            $today = Carbon::now();
            if(count($slots)>0 && $today->format('Y-m-d')==$start_date){
                $newFilteredslots=[];
                foreach($slots as $s){
                    $dtslot=Carbon::createFromFormat('Y-m-d H:i:s',$s->slot);
                    $newDateToday=Carbon::createFromFormat('Y-m-d H:i:s',$dtslot->format('Y-m-d').' '.$today->format('H:i:s'));
                    //echo $dtslot->format('Y-m-d H:i:s').'---->'.$newDateToday->format('Y-m-d H:i:s').'<br>';
                    
                    if($dtslot->greaterThanOrEqualTo($newDateToday)){
                        $newFilteredslots[]=$s;
                    }
                }
                //dd($newFilteredslots);
                $slots =$newFilteredslots;
            }
        }
        return view('reception.doctor-time-slots', ['slots'=>$slots,'doctor_id' => $doctor_id,'bookedSlots'=>$bookedSlots]);
    }
    public function formAppointment($appointment_id){
        $current_time = date('Y-m-d H:i:s');
        $appointment = null;
        $patients = Patient::all();
        $doctors = DB::select("SELECT officetimes.*, users.name, users.email FROM officetimes LEFT JOIN users ON users.id = officetimes.user_id GROUP BY user_id");

        if ($appointment_id > 0) {
                $appointment = Appointment::find ( $appointment_id );
        }
        return view('reception.form.appointment',['appointment' => $appointment,'patients'=>$patients,'doctors'=>$doctors,'current_time'=>$current_time]);
    }
    public function storeFormAppointment(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            $start_time = Carbon::createFromFormat('Y-m-d H:i',$request->start_time.' '.$request->SLOT);
            $data = array(
                'id'=>$request->id,
                'patient_id'=>$request->patient_id,
                'doctor_id'=>$request->doctor_id,
                'start_time'=>$start_time,
                'duration'=>$request->duration,
                'comments'=>$request->comments,
                'status'=>$request->status,
            );
            $appointment_id=$DbHelperTools->manageAppointment($data);
            $success = true;
            $msg = 'Your note have been saved successfully';
        }         
        return response ()->json ( [ 
                'success' => $success,
                'msg' => $msg 
        ] );
    }
    public function recorder(){
        return view('reception.form.recorder');
    }
    public function storeRecorde(Request $request) {
        dd($request->all());
        return response ()->json ( [ 
            'success' => true
        ] );
    }
}
