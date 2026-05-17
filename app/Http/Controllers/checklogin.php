<?php

namespace App\Http\Controllers;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
class checklogin extends Controller
{

function Checklogin(Request $request) {
    $email = $request->input('email');
    $pass = $request->input('password');

    $user = DB::table('users')->where('email', $email)->first();
    if ($user && Hash::check($pass, $user->password)) {
        session(['id' => $user->id, 'name' => $user->name]);
       
            return redirect('/Pos');

    } else {
        session()->flash('status', 'Invalid Email or Password');
        return redirect('/Login');
    }
}
   
    public function logout(Request $request) {
        Auth::logout(); // Logs out the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); 
        return redirect('/Login');
    }
  
}
