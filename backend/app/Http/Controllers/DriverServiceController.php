<?php

namespace App\Http\Controllers;

use App\TransportationBookingDeal;
use App\User;
use App\Friend;
use App\DriverService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverServiceController extends Controller
{
    public function getAllDriverServices(Request $request): JsonResponse
    {
        $driver_service = DriverService::select([
            'id',
            'user_id as userId',
            'title',
            'location',
            'state',
            'start_date',
            'end_date',
        ])
        ->where('state', 'free')
        ->where('user_id', '<>', auth()->user()->id)->get();

        if (!$driver_service) {
            return response()->json([
                'success' => true,
                'payload' => 'not_defined'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'payload' => $driver_service
            ]);
        }
    }

    public function getMyServices(Request $request): JsonResponse
    {
        $driver_service = DriverService::select([
                'id',
                'user_id as userId',
                'title',
                'location',
                'state',
                'start_date',
                'end_date',
            ])
            ->where('user_id', auth()->user()->id)->get();
        
        if (!$driver_service) {
            return response()->json([
                'success' => true,
                'payload' => 'not_defined'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'payload' => $driver_service
            ]);
        }
    }
  
    public function addDriverService(Request $request) : JsonResponse {
        
        $user = User::where([
            'id' => auth()->user()->id,
            'verified_driver' => 'yes'
        ]);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'driver_not_found'
            ], 404);
        }

        $driver_service = new DriverService();
        $driver_service->user_id    = auth()->user()->id;
        $driver_service->title      = $request->input('title');
        $driver_service->location   = $request->input('location');
        $driver_service->start_date = $request->input('start_date');
        $driver_service->end_date   = $request->input('end_date');
        $driver_service->state      = $request->input('state');
        $driver_service->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function updateDriverService(Request $request) : JsonResponse {
        $request->validate([
            'userId' => 'required'
        ]);

        $user = User::where([
            'id' => $request->input('userId'),
            'verified_driver' => 'yes'
        ]);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'driver_not_found'
            ], 404);
        }

        $driver_service = DriverService::where([
            'id' => $request->input('userId'),
            'service_id' => $request->input('service_id'),
        ]);

        $driver_service->title    = $request->input('title');
        $driver_service->location   = $request->input('location');
        $driver_service->start_date = $request->input('start_date');
        $driver_service->end_date   = $request->input('end_date');
        $driver_service->state      = $request->input('state');
        $driver_service->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function bookDriverService(Request $request) : JsonResponse {

        $user = User::where('id', $request->input('userId'))->first()->append('isFriendWithMe');

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ]);
        }

        // Create friend record
        $new_friend = new Friend;
        $new_friend->node_one_id = auth()->user()->id;
        $new_friend->node_two_id = $user->id;
        $new_friend->accepted = 1;
        $new_friend->save();

        return response()->json([
            'success' => true
        ]);
    }
}
