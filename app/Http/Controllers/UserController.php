<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    function Showuser(){
        // $data['user']= Users1::paginate(2);
        $data['user']=DB::table('users')->where('status',1)
        ->orderby('id','asc')
        ->get();
        return view('User' , $data);
    }


  public function update(Request $request)
{
    // Validate incoming request
    $request->validate([
        'id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'role' => 'required|string',
        'pass' => 'nullable|string|min:6', // password is optional
    ]);

    // Find user by ID
    $user = Users::findOrFail($request->id);

    // Update user data
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    // Only update password if provided
    if ($request->filled('pass')) {
        $user->password = bcrypt($request->pass);
    }

    $user->save();

    return redirect()->back()->with('success', 'User updated successfully.');
}


   public function Add(Request $rq)
{
    try {
        $rq->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'pass' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        DB::table('users')->insert([
            'name' => $rq->name,
            'email' => $rq->email,
            'password' => Hash::make($rq->pass),
            'role' => $rq->role,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch user list after insert
        $data['user'] = DB::table('users')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

             return redirect('/User')->with('success', 'User added successfully!');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
          // Make sure $data['user'] exists to avoid undefined variable error
        $data['user'] = DB::table('users')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
        return redirect('/User')->with('error', 'Failed to add user. Please try again.');
    }
}
public function destroy(Request $request)
{
    $user = Users::find($request->id);

    if ($user) {
        $user->update(['status' => 0]);
        return redirect('/User')->with('success', 'User deleted successfully.');
    } else {
        return redirect('/User')->with('error', 'User not found.');
    }
}

 public function Count() {
        $usercount = DB::table('users')->where('status', 1)
                                        ->count();
        
        return view('welcome')->with('usercount', $usercount);
    }


}
