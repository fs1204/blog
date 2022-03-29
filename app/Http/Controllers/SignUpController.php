<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\TokyoAddress;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    public function index()
    {
        return view('signup');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:20',],
            'email' => ['required','email:filter', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        auth()->login($user);

        return redirect()->route('mypage.blog');
    }
}
