<?php

namespace App\Http\Controllers;

use Log;
use App\ChatSession;
use App\Comment;
use App\DriverDocumentation;
use App\Cashout;
use App\Like;
use App\Message;
use App\Transaction;
use App\Notification;
use App\Notifications\FriendRequest;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\NewMessage;
use App\Notifications\PostComment;
use App\Notifications\PostLike;

use App\Events\FriendRequestEvent;

use App\Post;
use App\RegisteredPlate;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    // Get driver documentations
    public function getDriverDocumentations(Request $request):JsonResponse{
        $documents = DriverDocumentation::select([
            'id',
            'status',
            'driver_license as driverLicence',
            'vehicle_registration as vehicleRegistration',
            'insurance_card as insuranceCard',
            'vehicle_picture as vehiclePicture',
            'driver_headshot as driverHeadshot',
            'CREATED_AT as createdAt'
        ])
        ->where([
            'user_id' => $request->input('userId'),
        ])
        ->first();

        return response()->json([
            'success' => true,
            'payload' => $documents
        ]);
    }

    public function updateDriverDocumentations(Request $request) : JsonResponse{

        $request->validate([
            'documentId' => 'required',
            'action' => 'required'
        ]);

        $documents = DriverDocumentation::where([
            'id' => $request->input('documentId'),
        ])
        ->first();

        if($documents){
            $documents->status = $request->input("action");
            $documents->save();

            $driver = User::where('id', $request->input("userId"))->first();
            if($request->input('action') == "accepted"){
                $driver->verified_driver = 'yes';
                $driver->save();
            }
            
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function getTransactions(Request $request) : JsonResponse {
        $transactions = Transaction::select([
            'id',
            'transaction_uid',
            'user_id as userId',
            'amount',
            'description',
            'CREATED_AT as createdAt'
        ])->get();

        return response()->json([
            'success' => true,
            'payload' => $transactions
        ]);
    }
    public function getCashouts(Request $request) : JsonResponse {
        $cashouts = Cashout::select([
            'id',
            'user_id as userId',
            'status',
            'amount',
            'created_at as createdAt'
        ])->get();

        return response()->json([
            'success' => true,
            'payload' => $cashouts
        ]);
    }

    public function updateCashoutStatus(Request $request) : JsonResponse {
        $cashout = Cashout::where('id', $request->input('cashoutId'))->first();
        if($request->input('action') == 'accept'){
            $user = User::where("id", $cashout->user_id)->first();
            $user->balance = floatval($user->balance) - floatval($cashout->amount);
            $user->save();
            
            $cashout->status = 'accepted';
        }else{
            $cashout->status = 'rejected';
        }
        $cashout->save();

        return response()->json([
            'success' => true,
            'payload' => $cashout
        ]);
    }
}

