<?php

namespace App\Http\Controllers;

use App\RegisteredPlate;
use App\User;
use Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    // User sign up controller
    public function signUp(Request $request): JsonResponse
    {
        // Validate data
        $request->validate([
            'plateNumber' => 'required|alpha_num|min:3|max:20',
            'email' => 'required|email|unique:users',
            'firstName' => 'required|alpha',
            'lastName' => 'required|alpha',
            'password' => 'required|min:5',
            'phone' => 'required|numeric|unique:users'
        ]);

        // Check if plate number exists (owner)
        $plate_number = RegisteredPlate::where([
            'plate_number' => trim(strtoupper($request->input('plateNumber'))),
            'is_owner' => true
        ])->first();

        if ($plate_number) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'plateNumber' => [
                        "validation.unique"
                    ]
                ]
            ]);
        }

        // Create user
        $new_user = new User;

        $new_user->plate_number = trim(strtoupper($request->input('plateNumber')));
        $new_user->email = trim($request->input('email'));
        $new_user->first_name = trim($request->input('firstName'));
        $new_user->last_name = trim($request->input('lastName'));
        $new_user->password = Hash::make($request->input('password'));
        $new_user->phone = trim($request->input('phone'));

        $new_user->save();

        // Create registered plate
        $new_registered_plate = new RegisteredPlate;

        $new_registered_plate->user_id = $new_user->id;
        $new_registered_plate->plate_number = trim(strtoupper($request->input('plateNumber')));
        $new_registered_plate->is_owner = true;

        $new_registered_plate->save();

        // Send verification email with hash
        Log::info("Hash : " . Hash::make($new_user->created_at));
        
        return response()->json([
            'success' => true,
        ]);
    }

    // User authenticate/login controller
    public function authenticate(Request $request): JsonResponse
    {
        // Get user based on email or plate number
        $user = User::where('phone', $request->input('loginId'))
            ->orWhere('email', $request->input('loginId'))
            ->first();

        if ($user) {
            // Check password
            if (Hash::check($request->input('password'), $user->password)) {

                // Check if the account is verified
                if ($user->verified) {
                    // Return token (attempt) and success message
                    return response()->json([
                        'success' => true,
                        'token' => auth('api')->attempt(['email' => $user->email, 'password' => $request->input('password')]),
                        'userId' => $user->id,
                        'firstname' => $user->first_name,
                        'lastname'  => $user->last_name
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'account_not_verified'
                    ], 401);
                }
            }

            return response()->json([
                'success' => false,
                'error' => 'wrong_credentials'
            ], 401);
        }

        return response()->json([
            'success' => false,
            'error' => 'wrong_credentials'
        ], 401);
    }

    // Authorize the user
    public function checkAuthentication(Request $request): JsonResponse
    {
        // The middle ware will take care of error
        return response()->json([
            'success' => true
        ]);
    }

    // Profile pic
    public function profilePic($user_id): BinaryFileResponse
    {
        $user = User::where('id', $user_id)->first();
        
        if ($user) {
            return response()->file(storage_path('app/profile_pics/' . $user->profile_pic));
            // return Storage::url($user->profile_pic);
        }

        return ErrorsController::userNotFoundError();
    }
}
