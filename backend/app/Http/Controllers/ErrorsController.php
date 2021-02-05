<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function authenticationError(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => 'invalid_authentication'
        ]);
    }

    public static function userNotFoundError(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => 'user_not_found'
        ]);
    }

    public function test()
    {
        return Carbon::now()->year;
    }
}
