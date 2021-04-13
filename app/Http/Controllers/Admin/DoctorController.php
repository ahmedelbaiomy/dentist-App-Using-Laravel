<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;


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

}
