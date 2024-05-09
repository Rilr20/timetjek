<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeRegistration;
use Carbon\Carbon;
class TimeRegistrationController extends Controller
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
        // find first if one is empty then add to it or just create new one.
        $timeRegistration = TimeRegistration::where('user_id',$request->user_id)->orderBy('created_at', 'desc')->first();
        if ($timeRegistration == NULL || $timeRegistration->check_out_time != null) {

            $timeRegistration = new TimeRegistration;
            $timeRegistration->user_id = $request->user_id;
            $timeRegistration->check_in_time = Carbon::now();
            $timeRegistration->gps_coordinates = $request->gps_coordinates;
            $timeRegistration->save();
            
            return response()->json($timeRegistration, 201);
        } else {

            // $timeRegistration->gps_coordinates = $request->gps_coordinates;
            $timeRegistration->check_out_time = Carbon::now();
            $timeRegistration->save();
            return response()->json($timeRegistration, 201);
        }
    }
    /**
     * Gets table from one user
     */
    public function table(Request $request)
    {
        return TimeRegistration::where('user_id', $request->user_id)->get();
    }
    /**
     * Display the specified resource.
     */
    public function show(string $userId)
    {
        //
        return TimeRegistration::where('user_id', $userId)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if ($request->check_in_time <= $request->check_out_time) {
            $timeRegistration = TimeRegistration::findOrFail($id);
            $timeRegistration->check_in_time = Carbon::createFromTimestamp($request->check_in_time);
            $timeRegistration->check_out_time = Carbon::createFromTimestamp($request->check_out_time);
            $timeRegistration->gps_coordinates = $request->gps_coordinates;
            $timeRegistration->save();
    
            return response()->json($timeRegistration, 200);
        } else {
            return response()->json([
                'error' => 'Check out time cannot be before check in time.'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
