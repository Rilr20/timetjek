<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\support\Str;
use App\Http\Controllers\UserController;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('personnumber', $request->personnumber)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = Str::random(30);
        $user->remember_token = $token;
        $user->save();
        return response()->json(['token', $token, 'user_id', $user->id], 200);
    }
    public function logout(Request $request) {
            $user = User::where('personnumber', $request->personnumber)->first();
            $user->remember_token = null;
            return response()->json("logged out", 200);
    }
    public function register(Request $request) {
        $user = User::where('personnumber', $request->personnumber)->first();
        if ($user == null) {
            $user = new User;

            $user->personnumber = $request->personnumber;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json($user, 201);
        } else {
            return response()->json(["message", "Account already registered"], 400);
        }
    }
}
