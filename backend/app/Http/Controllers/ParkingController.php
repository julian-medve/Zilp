<?php

namespace App\Http\Controllers;

use App\ParkingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'parkingPhoto' => 'mimes:jpeg,jpg,png|max:2000',
            'location' => 'required',
            'description' => 'required',
            'hourlyRate' => 'required',
            'dailyRate' => 'required'
        ]);

        $new_parking_service = new ParkingService;

        $new_parking_service->user_id = auth()->user()->id;

        if ($request->file('parkingPhoto')->isValid()) {
            $upload_file = $request->file('parkingPhoto')->store('parking_photos');
            $new_parking_service->parking_photo = explode('/', $upload_file)[1];
        }

        $new_parking_service->description = $request->input('description');
        $new_parking_service->location = $request->input('location');
        $new_parking_service->hourly_rate = $request->input('hourlyRate');
        $new_parking_service->daily_rate = $request->input('dailyRate');

        $new_parking_service->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function get(Request $request): JsonResponse
    {
        $parking_services = ParkingService::orderBy('id', 'desc')
            ->with([
                'user' => function ($query) {
                    $query->select([
                        'id',
                        'first_name as firstName',
                        'last_name as lastName'
                    ]);
                }
            ])
            ->get();

        return response()->json([
            'success' => true,
            'payload' => $parking_services
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $parking_services = ParkingService::orderBy('id', 'desc')
            ->with([
                'user' => function ($query) {
                    $query->select([
                        'id',
                        'first_name as firstName',
                        'last_name as lastName'
                    ]);
                }
            ]);

        if ($request->has('location')) {
            $parking_services->where('location', 'LIKE', '%' . $request->input('location') . '%');
        }

        return response()->json([
            'success' => true,
            'payload' => $parking_services->get()
        ]);
    }
}
