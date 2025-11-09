<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->role == 'gudang'){
                $url = url('dashboard-gudang');
            }else if(Auth::user()->role == 'tracking'){
                $url = url('dashboard-tracking');
            }else{
                $url = url('dashboard');
            }
            return redirect($url);
        }
//        return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
        return back()->with('error', 'Password salah!')->withInput();
    }

    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
        return Redirect::to("login")->withSuccess('Opps! You do not have access');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }

    public function reset_password(Request $request)
    {
        $user = Auth()->user();

        $input = $request->validate([
            'old_password' => ['required', 'string',
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Kata sandi saat ini tidak sesuai.');
                    }
                }
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password']
        ]);

        $user->password = Hash::make($input['password']);
        $user->save();

        Session::flush();
        Auth::logout();
        return view('auth.login');
    }
}
