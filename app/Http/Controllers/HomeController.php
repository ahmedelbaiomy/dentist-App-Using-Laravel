<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        return view('home');
    }

    public function account_setting()
    {
        return view('account_setting');
    }

    public function changepassword(Request $request)
    {
        $this->validate($request, 
        [
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $id             = auth()->user()->id;
        $pass           = $request->password;
        $user           = User::findOrFail($id);
        $user->password = Hash::make($pass);
        $user->save();
        return back()->with('success', 'User Data is successfully updated');
    }
}
