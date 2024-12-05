<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'fitbit_user_id' => 'required',
            'password' => 'required',
        ]);

        // 查询用户
        $user = User::where('fitbit_user_id', $request->fitbit_user_id)->first();

      // LoginController.php
if ($user && Hash::check($request->password, $user->password)) {
    // 存储用户登录信息到 session
    session(['fitbit_user_id' => $user->fitbit_user_id]); // 使用 fitbit_user_id 存储

    return redirect()->route('user.comparison') // 不需要传递参数
        ->with('success', 'Login successful!');
}


        // 登录失败返回错误
        return back()->withErrors(['login' => 'Invalid Fitbit User ID or password.']);
    }

    public function logout(Request $request)
    {
        session()->forget('user_id');
        return redirect()->route('user.login')->with('success', 'Logged out successfully.');
    }
}
