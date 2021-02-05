<?php

namespace App\Http\Controllers;

use App\TransportationRentingDeal;
use App\TransportationSellingDeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellingController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'vehiclePhoto' => 'mimes:jpeg,jpg,png|max:2000',
            'make' => 'required',
            'model' => 'required',
            'year' => 'required',
            'color' => 'required',
            'accessories' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        $new_transportation_selling_deal = new TransportationSellingDeal;

        if ($request->file('vehiclePhoto')->isValid()) {
            $upload_file = $request->file('vehiclePhoto')->store('vehicle_photos');
            $new_transportation_selling_deal->vehicle_photo = explode('/', $upload_file)[1];
        }

        $new_transportation_selling_deal->user_id = auth()->user()->id;
        $new_transportation_selling_deal->make = $request->input('make');
        $new_transportation_selling_deal->model = $request->input('model');
        $new_transportation_selling_deal->year = $request->input('year');
        $new_transportation_selling_deal->color = $request->input('color');
        $new_transportation_selling_deal->accessories = $request->input('accessories');
        $new_transportation_selling_deal->description = $request->input('description');
        $new_transportation_selling_deal->price = $request->input('price');
        $new_transportation_selling_deal->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function get(Request $request): JsonResponse
    {
        $transportation_selling_deals = TransportationSellingDeal::orderBy('id', 'desc')
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
            'payload' => $transportation_selling_deals
        ]);
    }

    public function priceRange(Request $request): JsonResponse
    {
        // Min price
        $min_price = (TransportationSellingDeal::orderBy('price', 'asc')->first())->price;

        // Max price
        $max_price = (TransportationSellingDeal::orderBy('price', 'desc')->first())->price;

        return response()->json([
            'success' => true,
            'payload' => [
                'min' => $min_price,
                'max' => $max_price
            ]
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $transportation_selling_deals = TransportationSellingDeal::orderBy('id', 'desc')
            ->with([
                'user' => function ($query) {
                    $query->select([
                        'id',
                        'first_name as firstName',
                        'last_name as lastName'
                    ]);
                }
            ]);

        if(!empty($request->input('make'))) {
            $transportation_selling_deals->where('make', $request->input('make'));
        }

        if(!empty($request->input('model'))) {
            $transportation_selling_deals->where('model', $request->input('make'));
        }

        if(!empty($request->input('year'))) {
            $transportation_selling_deals->where('year', $request->input('year'));
        }

        if(!empty($request->input('color'))) {
            $transportation_selling_deals->where('color', $request->input('color'));
        }

        if($request->has('minPrice')) {
            $transportation_selling_deals->where('price', '>=', intval($request->input('minPrice')));
        }

        if($request->has('maxPrice')) {
            $transportation_selling_deals->where('price', '<=', intval($request->input('maxPrice')));
        }

        return response()->json([
            'success' => true,
            'payload' => $transportation_selling_deals->get()
        ]);
    }
}
