<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // var_dump($request);
        $user = User::where('personnumber', $request->personnumber)->first();
        var_dump($user);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        // $user->personnumber = $request->personnumber;
        $user->save();
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
