<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function registerForm()
    {
        return view('pages.register');
    }

    //регистрация - создание нового пользователя.
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => "required",
        ]);

        $user = User::add($request->all());
        $user->generatePassword($request->get('password'));

        return redirect('/login');
    }

    public function loginForm()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ])) {
            return redirect('/');
        }
        return redirect()->back()->with('status', 'wrong login or password');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function profileForm()
    {
        $user = auth::user();

        return view('pages.profile', ['user' => $user]);
    }

    public function profile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'avatar' => 'nullable|image'
        ]);

        $user =Auth::user();
        $user->edit($request->all());
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));

        return redirect()->back()->with('ststus', 'Profile successully updated');
    }
}
