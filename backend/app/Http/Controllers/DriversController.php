<?php

namespace App\Http\Controllers;

use App\TransportationBookingDeal;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    public function availability(Request $request): JsonResponse
    {
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

        $transportation_booking_deal = TransportationBookingDeal::where('user_id', $request->input('userId'))->first();

        if (!$transportation_booking_deal) {
            return response()->json([
                'success' => true,
                'payload' => [
                    'availability' => 'not_defined'
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'payload' => [
                    'availability' => [
                        'monday' => $transportation_booking_deal->available_monday,
                        'tuesday' => $transportation_booking_deal->available_tuesday,
                        'wednesday' => $transportation_booking_deal->available_wednesday,
                        'thursday' => $transportation_booking_deal->available_thursday,
                        'friday' => $transportation_booking_deal->available_friday,
                        'saturday' => $transportation_booking_deal->available_saturday,
                        'sunday' => $transportation_booking_deal->available_sunday
                    ]
                ]
            ]);
        }
    }

    public function updateAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean'
        ]);

        $driver = User::where([
            'user_id' => auth()->user()->id,
            'verified_driver' => 'yes'
        ]);

        if (!$driver) {
            return response()->json([
                    'success' => false,
                    'error' => 'driver_not_found'
                ] . 404);
        }

        $transportation_booking_deal = TransportationBookingDeal::where('user_id', auth()->user()->id)->first();

        if ($transportation_booking_deal) {
            $transportation_booking_deal->available_monday = $request->input('monday');
            $transportation_booking_deal->available_tuesday = $request->input('tuesday');
            $transportation_booking_deal->available_wednesday = $request->input('wednesday');
            $transportation_booking_deal->available_thursday = $request->input('thursday');
            $transportation_booking_deal->available_friday = $request->input('friday');
            $transportation_booking_deal->available_saturday = $request->input('saturday');
            $transportation_booking_deal->available_sunday = $request->input('sunday');
            $transportation_booking_deal->save();
        } else {
            $new_transportation_booking_deal = new TransportationBookingDeal;
            $new_transportation_booking_deal->user_id = auth()->user()->id;
            $new_transportation_booking_deal->available_monday = $request->input('monday');
            $new_transportation_booking_deal->available_tuesday = $request->input('tuesday');
            $new_transportation_booking_deal->available_wednesday = $request->input('wednesday');
            $new_transportation_booking_deal->available_thursday = $request->input('thursday');
            $new_transportation_booking_deal->available_friday = $request->input('friday');
            $new_transportation_booking_deal->available_saturday = $request->input('saturday');
            $new_transportation_booking_deal->available_sunday = $request->input('sunday');
            $new_transportation_booking_deal->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function getAvailableDrivers(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required'
        ]);

        $day_of_week = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ][Carbon::parse($request->input('date'))->dayOfWeek];

        $transportation_booking_deals = TransportationBookingDeal::where('available_' . $day_of_week, true)
            ->with([
                'user' => function($query) {
                    $query->select([
                        'id',
                        'plate_number AS plateNumber',
                        'first_name AS firstName',
                        'last_name AS lastName'
                    ]);
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'payload' => $transportation_booking_deals
        ]);
    }
}
