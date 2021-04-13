<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
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
        $patients = Patient::all();
        $doctors = User::all()->where('user_type', 'doctor');
        $services = Service::all();
        $appointments = Appointment::all();
        $users = User::all();
        $services=Service::all();
        return view('admin',compact('appointments', 'patients', 'doctors','users','services'));
    }
}
