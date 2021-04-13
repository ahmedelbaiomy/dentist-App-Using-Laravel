<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


class UserController extends Controller
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
        $users = User::all();
        return view('admin.users',compact('users'));
    }

    

    public function setstate($id, $key)
    {
            $user = User::findOrFail($id);
            $user->state = $key;
            $user->save();
            return redirect('/admin/users')->with('success', 'User Data is successfully updated');
    }

    

    public function resetpass($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('12345');
        $user->save();
        return redirect('/admin/users')->with('success', 'User Data is successfully updated');
    }

    public function settype(Request $data)
    {
        $user = User::findOrFail($data['id']);
        $user->user_type = $data['user_type'];
        $user->save();
        return response()->json(['success'=>'Ajax request submitted successfully']);
    }
    

    public function destroy($id)
    {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect('/admin/users')->with('success', 'User Data is successfully deleted');
    }


}
