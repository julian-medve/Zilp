<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GeneralController extends Controller
{
    // Get image file
    public function images($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/images/' . $file_name));
    }

    public function vehiclePhotos($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/vehicle_photos/' . $file_name));
    }

    public function parkingPhotos($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/parking_photos/' . $file_name));
    }

    public function driverPhotos($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/driver_documentations/' . $file_name));
    }

    // Pusher authenticate
    public function pusherAuthenticate(Request $request)
    {
        $socket_id = $request->input('socket_id');
        $channel_name = $request->input('channel_name');

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('1004766'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        ]);

        $key = $pusher->presence_auth($channel_name, $socket_id, auth('api')->user()->id, ['id' => auth('api')->user()->id]);

        return response($key);
    }
}
