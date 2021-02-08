<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ActivityController extends Controller
{
    public function getAllActivities(Request $request) : JsonResponse
    {
        $activity = Activity::where('user_id', auth()->user()->id)->get();

        if(!$activity)
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        else 
            return response()->json([
                'success' => true,
                'payload' => $activity
            ]);
    }
    
    public function addActivity(Request $request) : JsonResponse
    {
        $request->validate([
            'title' => 'required'
        ]);

        $activity = new Activity;
        $activity->user_id = auth()->user()->id;
        $activity->title = $request->input('title');
        $activity->save();

        return response()->json([
            'success' => true,
            'payload' => $activity
        ]);
    }
}
