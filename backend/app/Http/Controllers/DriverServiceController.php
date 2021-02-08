<?php

namespace App\Http\Controllers;

use App\TransportationBookingDeal;
use App\User;
use App\DriverService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverServiceController extends Controller
{
    public function getDriverService(Request $request): JsonResponse
    {
        if(auth()->user()->verified_driver == "no")
            $driver_service = DriverService::select([
                'id',
                'user_id',
                'title',
                'location',
                'state',
                'start_date',
                'end_date',
            ])
            ->where('state', 'free')->get();
        else
            $driver_service = DriverService::select([
                'id',
                'user_id',
                'title',
                'location',
                'state',
                'start_date',
                'end_date',
            ])->where('user_id', auth()->user()->id)->get();

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
}
