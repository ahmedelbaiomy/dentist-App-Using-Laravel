<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Library\Services\DbHelperTools;


class DoctorController extends Controller
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
        // $doctors = User::all()->where('user_type', 'doctor');
        // return view('admin.users',compact('users'));

        $doctors = DB::table('users')
            ->leftJoin('doctors', 'users.id', '=', 'doctors.user_id')
            ->select('users.id', 'users.name', 'users.email', 'users.state', 'doctors.id AS d_id', 'doctors.birthday', 'doctors.address', 'doctors.phone', 'doctors.birthday','doctors.target')
            ->where('users.user_type', '=', 'doctor')
            ->groupBy('users.id')
            ->get();
        return view('admin.doctor', compact('doctors'));
    }

    public function settarget(Request $data) 
    {
        if($data['id'] == null){
            DB::table('doctors')->insert(
                ['user_id' => $data['user_id'], 'target' => $data['target']]
            );
        } else {
            $doctor = Doctor::findOrFail($data['id']);
            $doctor->target = $data['target'];
            $doctor->save();
        }
        
        return response()->json(['success'=>'Ajax request submitted successfully']);
    }


    public function search(Request $data) 
    {
        $start_time = date('Y-M-d h:i:s', strtotime($data['start_time'])); 
        $finish_time = date('Y-M-d h:i:s', strtotime($data['finish_time'])); 
        $doctors = DB::table('users')
            ->leftJoin('doctors', 'users.id', '=', 'doctors.user_id')
            ->leftJoin('appointments', 'doctors.doctor_id', '=', 'doctors.id')
            ->select('users.id', 'users.name', 'users.email', 'users.state', 'doctors.id AS d_id', 'doctors.birthday', 'doctors.address', 'doctors.phone', 'doctors.birthday','doctors.target')
            ->where('users.user_type', '=', 'doctor')
            ->where(function ($query) {
                $query->whereDate('appointments.start_time', '>=', $start_time)
                      ->orWhereDate('appointments.finsh_time', '<=', $finish_time);
            })
            ->groupBy('users.id')
            ->get();

        return view('admin.doctor', compact('doctors'));
    }
    public function schedules()
    {
        return view('admin.schedule.index');
    }
    public function jsonDoctorsForSelect(){
        $result=[];
        $rows = DB::table('users')
            ->leftJoin('doctors', 'users.id', '=', 'doctors.user_id')
            ->select('users.id', 'users.name', 'users.email', 'users.state', 'doctors.id AS d_id', 'doctors.birthday', 'doctors.address', 'doctors.phone', 'doctors.birthday','doctors.target')
            ->where('users.user_type', '=', 'doctor')
            ->groupBy('users.id')
            ->get();
        if(count($rows)>0){
            foreach($rows as $doctor){
                $result[]=['id'=>$doctor->id,'name'=>$doctor->email];
            }
        }
        return response()->json($result);
    }
    public function getSchedules($doctor_id,$day){
        $schedules = null;
        if($doctor_id>0 && $day!=''){
            $schedules = Schedule::where([['day',$day],['doctor_id',$doctor_id]])->get();
        }
        return view('admin.schedule.list', ['schedules'=>$schedules,'doctor_id' => $doctor_id,'day'=>$day]);
    }
    public function formSlot($doctor_id,$day){
        return view('admin.schedule.form.form',['doctor_id' => $doctor_id,'day'=>$day]);
    }
    public function storeFormSlot(Request $request) {
        //dd(Carbon::createFromFormat('Y-m-d H:i','2021-04-16 09:30')->format('l'));//return 'Friday'
        //SELECT `id`,`start_hour`,`end_hour`,WEEKDAY(start_hour) FROM `af_schedules` WHERE WEEKDAY(start_hour) = 4
        //$number_day_of_week = 4;
        //$users = DB::table('af_schedules')->select('id','start_hour')->whereRaw(DB::raw('WEEKDAY(start_hour) = '.$number_day_of_week))->get();
        //lundi - TUESDAY 1
        //mardi - TUESDAY 2
        //mercredi - WEDNESDAY 3
        //jeudi - THURSDAY 4
        //vendredi - FRIDAY 5
        //samedi - SATURDAY 6
        //dimanche - SUNDAY si dayOfWeekIso ->7 or si dayOfWeek ->0
        //dd(Carbon::createFromFormat('Y-m-d H:i','2021-04-19 09:30')->dayOfWeek);//1
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        if ($request->isMethod('post')) {
            $doctor_id = $request->doctor_id;
            $day = $request->day;
            $dNow = Carbon::now();
            $started_at = Carbon::createFromFormat('Y-m-d H:i',$dNow->format('Y-m-d').' '.$request->started_at);
            $ended_at = Carbon::createFromFormat('Y-m-d H:i',$dNow->format('Y-m-d').' '.$request->ended_at);
            $DbHelperTools=new DbHelperTools();
            $dates = $DbHelperTools->generateDateRange($started_at->format('Y-m-d H:i'),$ended_at->format('Y-m-d H:i'),$request->slot_duration);
            dd($dates);
            if(count($dates)>0){
                $doctor_existing_time_slots = $DbHelperTools->getTimeSlotsByDoctorDay($doctor_id,$day);
                foreach($dates as $date=>$times){
                    if(count($times)){
                        foreach($times as $t){
                            if(!in_array($t,$doctor_existing_time_slots)){
                                $slot = Carbon::createFromFormat('Y-m-d H:i:s',$date.' '.$t);
                                $data = array(
                                    'id'=>$request->id,
                                    'day'=>$day,
                                    'slot'=>$slot,
                                    'doctor_id'=>$doctor_id,
                                );
                                $schedule_id=$DbHelperTools->manageSchedule($data);
                            }
                        }
                    }
                }
                $success = true;
                $msg = 'Time Slots slices have been saved successfully';
            }
        }         
        return response ()->json ( [ 
                'success' => $success,
                'msg' => $msg 
        ] );
    }
}
