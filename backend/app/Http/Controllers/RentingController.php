<?php

namespace App\Http\Controllers;

use App\TransportationRentingDate;
use App\TransportationRentingDeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentingController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'vehiclePhoto' => 'mimes:jpeg,jpg,png|max:2000',
            'make' => 'required',
            'model' => 'required',
            'year' => 'required',
            'color' => 'required',
            'conditionDescription' => 'required',
            'accessories' => 'required',
            'description' => 'required',
            'rentalPrice' => 'required',
            'availableDates' => 'required'
        ]);


        $new_transportation_renting_deal = new TransportationRentingDeal();

        if ($request->file('vehiclePhoto')->isValid()) {
            $upload_file = $request->file('vehiclePhoto')->store('vehicle_photos');
            $new_transportation_renting_deal->vehicle_photo = explode('/', $upload_file)[1];
        }

        $new_transportation_renting_deal->user_id = auth()->user()->id;
        $new_transportation_renting_deal->make = $request->input('make');
        $new_transportation_renting_deal->model = $request->input('model');
        $new_transportation_renting_deal->year = $request->input('year');
        $new_transportation_renting_deal->color = $request->input('color');
        $new_transportation_renting_deal->condition_description = $request->input('conditionDescription');
        $new_transportation_renting_deal->accessories = $request->input('accessories');
        $new_transportation_renting_deal->description = $request->input('description');
        $new_transportation_renting_deal->rental_price = $request->input('rentalPrice');
        $new_transportation_renting_deal->save();

        foreach (explode(',', $request->input('availableDates')) as $available_date) {
            $new_transportation_renting_date = new TransportationRentingDate;
            $new_transportation_renting_date->rent_id = $new_transportation_renting_deal->id;
            $new_transportation_renting_date->available_date = $available_date;
            $new_transportation_renting_date->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required'
        ]);

        $transportation_renting_dates = TransportationRentingDate::where('available_date', $request->input('date'))->get();

        if(!$transportation_renting_dates) {
            return response()->json([
                'success' => false,
                'error' => 'no_available_renting_found'
            ]);
        }

        $transportation_renting_deals = TransportationRentingDeal::whereIn('id', $transportation_renting_dates->pluck('rent_id'))
            ->with([
                'user' => function($query) {
                    $query->select([
                        'id',
                        'first_name as firstName',
                        'last_name as lastName'
                    ]);
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'payload' => $transportation_renting_deals
        ]);
    }
}
