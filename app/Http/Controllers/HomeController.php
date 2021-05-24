<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
use Carbon;

class HomeController extends Controller
{
    public function authenticate()
    {
        return view('login');
    }

    public function postlogin(Request $request)
    {
        if(Auth::attempt($request->only('username','password'))){
            if(Auth::user()->ROLE == 1){
                return redirect()->route('admin');
            }
            elseif(Auth::user()->ROLE == 2){
                return redirect()->route('pic');    
            }
            elseif(Auth::user()->ROLE == 3){
                return redirect()->route('investor');    
            }
        }      
        session()->flash('error', 'Invalid Username or Password');
        
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();

        return redirect ('/');
    }

    public function updatePassword(Request $request){
        $user = User::where('username', '=', Auth::user()->username)->first();
        $user->password = Hash::make($request->new_password);
        $user->updated_at = Carbon::now();
        $user->save();

        if(Auth::user()->ROLE == 1){
            return redirect()->route('admin');
        }
        elseif(Auth::user()->ROLE == 2){
            return redirect()->route('pic');
        }
        elseif(Auth::user()->ROLE == 3){
            return redirect()->route('investor');
        }
    }

    public function verify_old_password()
    {
        $old_password = $_POST['old_password'];
        $verify_result =  HASH::check($old_password, Auth::user()->password);
        return response()->json($verify_result);
    }

    public function profile()
    {
        $data = User::findOrFail(Auth::user()->ID_USER)->first();

        return view('profile', compact('data'));
    }

    public function gantiPassword()
    {

        return view('change-password');
    }

    public function username()
    {
        return 'username';
    }
}
