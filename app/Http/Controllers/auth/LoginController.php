<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;


use App\Mail\testMail;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
    
        $validate = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        if($validate->fails()){
            return response()->json(['message'=>$validate->errors()],400);
        }
        $credentials = $request->only('email', 'password');
        $user = User::where('email',$request->email)->first();
        if($user)
        {
            if (Auth::attempt($credentials)) {
                return redirect()->to('/');
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        }else{
            return response()->json(['error' => 'Email not found'], 401);

        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('status', 'You have been logged out.');
    }
}
