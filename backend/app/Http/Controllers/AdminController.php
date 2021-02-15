<?php

namespace App\Http\Controllers;

use Log;
use App\ChatSession;
use App\Comment;
use App\DriverDocumentation;
use App\Friend;
use App\Like;
use App\Message;
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

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }
}

