<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fitbit_user_id' => 'required|exists:users,fitbit_user_id',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('fitbit_user_id', $request->fitbit_user_id)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('user.login')->with('success', 'Registration successful. Please log in.');
        }

        return redirect()->back()->withErrors(['fitbit_user_id' => 'Fitbit User ID not found.']);
    }
}
