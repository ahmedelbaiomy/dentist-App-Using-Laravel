<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
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
        $patients = Patient::all();
        $doctors = User::all()->where('user_type', 'doctor');
        $services = Service::all();
        $appointments = Appointment::all();
        $users = User::all();
        $services=Service::all();
        return view('dashboard.admin',compact('appointments', 'patients', 'doctors','users','services'));
    }
    public function sdtDoctorStats(Request $request)
    {
        $DbHelperTools=new DbHelperTools();
        $data=$meta=[];
        //$doctors = Doctor::orderByDesc('id')->get();

        $doctors = Doctor::join('users', 'doctors.user_id', '=', 'users.id')->get(['doctors.*', 'users.name', 'users.email']);

        foreach ($doctors as $d) {
            $stats=$DbHelperTools->getStatsByDoctors($d->id);
            $row=array();
            //th>Doctor</th>
            $row[]='<div class="d-flex align-items-center"><div><div class="font-weight-bolder">'.$d->name.'</div><div class="font-small-2 text-muted">'.$d->email.'</div></div></div>';
            //<th>Income</th>
            $row[]='$'.$stats['incomes'];
            //<th>Refund</th>
            $row[]='$'.$stats['refunds'];
            //<th>Appointment</th>
            $row[]='';
            //<th>Patient</th>
            $row[]='';
            $data[]=$row;
        }    
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
}
