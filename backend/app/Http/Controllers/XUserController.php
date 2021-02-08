<?php

namespace App\Http\Controllers;

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

class XUserController extends Controller
{
    // Log out
    public function logOut(): JsonResponse
    {
        Cache::forget('user-is-online-' . auth()->user()->id);
        auth()->logout();

        return response()->json([
            'success' => true
        ]);
    }

    // Send a post
    public function sendPost(Request $request): JsonResponse
    {
        $request->validate([
            'postContent' => 'required_unless:postMetaType,IMAGE',
            'postMetaType' => 'required|in:NONE,IMAGE,VIDEO,LOCATION',
            'imageFile' => 'mimes:jpeg,jpg,png'
        ]);

        $new_post = new Post;
        $new_post->user_id = auth()->user()->id;
        $new_post->post_content = strip_tags($request->input('postContent'), '<b><i><a><br><p>');
        $new_post->post_meta_type = $request->input('postMetaType');

        if ($request->input('postMetaType') === 'IMAGE' && $request->file('imageFile')->isValid()) {
            $new_post->post_meta_content = $request->file('imageFile')->store('images');
        }

        $new_post->save();

        return response()->json([
            'success' => true,
            'id' => $new_post->id
        ]);
    }

    // Profile info
    public function profileInfo(): JsonResponse
    {
        $user = User::select([
            'id',
            'first_name AS firstName',
            'last_name AS lastName',
            'plate_number AS plateNumber',
            'guest_plate AS guestPlate',
            'balance',
            'verified_driver AS verifiedDriver',
            'email',
            'phone'
        ])
            ->where('id', auth()->user()->id)
            ->with('notifications')
            ->first()
            ->append('notificationsCount');

        return response()->json([
            'status' => 'success',
            'payload' => $user
        ]);
    }

    // Profile pic
    public function profilePic(): BinaryFileResponse
    {
        return response()->file(storage_path('app/profile_pics/' . auth()->user()->profile_pic));
    }

    public function images($filename): BinaryFileResponse
    {
        return response()->file(storage_path($filename));
    }

    // Friends list
    public function friendsList(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'payload' => auth()->user()->getFriendsList()
        ]);
    }

    // Update status
    public function updateStatus(): JsonResponse
    {
        // This controller is only used to update user's online status
        // which is taken care of by the UserActivityStatus middleware
        // and is here in case there might be other use cases later on

        return response()->json([
            'success' => true
        ]);
    }

    // Feed
    public function feed(): JsonResponse
    {
        $ids_list = [auth()->user()->id];

        foreach (auth()->user()->getFriendsList() as $friend) {
            $ids_list[] = $friend->id;
        }

        $posts_ids = Post::whereIn('user_id', $ids_list)
            ->select(['id'])
            ->orderBy('id', 'DESC')
            ->get()
            ->pluck('id');

        return response()->json([
            'success' => true,
            'payload' => $posts_ids
        ]);
    }

    // Post
    public function getPost($id): JsonResponse
    {
        $post = Post::select([
            'id',
            'post_meta_type AS postMetaType',
            'post_meta_content AS postMetaContent',
            'post_content AS description',
            'user_id',
            'CREATED_AT AS time'
        ])->with([
            'user' => function ($q) {
                return $q->select([
                    'id',
                    'first_name AS firstName',
                    'last_name AS lastName',
                    'plate_number AS plateNumber'
                ]);
            }
        ])->where('id', $id)->first();

        $post->user->name = $post->user->firstName . ' ' . $post->user->lastName;
        $post->comments = '';

        return response()->json([
            'success' => true,
            'payload' => $post
        ]);
    }

    public function getAllPosts(): JsonResponse
    {
        $posts = Post::select([
            'id',
            'post_meta_type AS postMetaType',
            'post_meta_content AS postMetaContent',
            'post_content AS description',
            'user_id',
            'CREATED_AT AS time'
        ])->with([
            'user' => function ($q) {
                return $q->select([
                    'id',
                    'first_name AS firstName',
                    'last_name AS lastName',
                    'plate_number AS plateNumber',
                ]);
            }
        ])
        ->orderBy('time', 'DESC')->get();

        foreach($posts as $post){
            $post->user->name = $post->user->firstName . ' ' . $post->user->lastName;
            $post->comments = '';
            $post->user->profile = '';
        }

        return response()->json([
            'success' => true,
            'payload' => $posts
        ]);
    }

    // Like post
    public function likePost($id): JsonResponse
    {
        $like = Like::where([
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ])->first();

        if (!$like) {
            $new_like = new Like;
            $new_like->user_id = auth()->user()->id;
            $new_like->post_id = $id;
            $new_like->save();

            $post = Post::where('id', $id)->first();
            $user = User::where('id', $post->user_id)->first();

            if (auth()->user()->id !== $user->id) $user->notify(new PostLike(auth()->user(), $post->id));

        } else {
            $like->delete();
        }

        return response()->json([
            'success' => true
        ]);
    }

    // Send a comment
    public function sendComment(Request $request, $id): JsonResponse
    {
        $request->validate([
            'commentContent' => 'required'
        ]);

        $post = Post::where('id', $id)->first();

        $new_comment = new Comment;
        $new_comment->post_id = $post->id;
        $new_comment->user_id = auth()->user()->id;
        $new_comment->comment_content = strip_tags($request->input('commentContent'), '<b><i><a><br><p>');
        $new_comment->save();

        $user = User::where('id', $post->user_id)->first();
        if (auth()->user()->id !== $user->id) $user->notify(new PostComment(auth()->user(), $post->id));

        return response()->json([
            'success' => true
        ]);
    }

    // Get comments
    public function getComments($id): JsonResponse
    {
        $comments = Comment::select([
            'id',
            'post_id AS postId',
            'user_id AS userId',
            'comment_content AS commentContent',
            'user_id'
        ])
            ->where('post_id', $id)->with([
                'user' => function ($q) {
                    $q->select([
                        'id',
                        'first_name AS firstName',
                        'last_name AS lastName',
                        'plate_number AS plateNumber'
                    ]);
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'payload' => $comments
        ]);
    }

    // Public profile
    public function publicProfile($plate_number): JsonResponse
    {
        $user = User::select([
            'id',
            'first_name AS firstName',
            'last_name AS lastName',
            'plate_number AS plateNumber',
            'guest_plate AS guestPlate'
        ])
            ->where('plate_number', $plate_number)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ]);
        }

        $user->append(['isFriendWithMe', 'feed']);

        return response()->json([
            'success' => true,
            'payload' => $user
        ]);
    }

    // Add friend
    public function addFriend(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|numeric'
        ]);

        if (auth()->user()->id == $request->input('userId')) {
            return response()->json([
                'success' => false,
                'error' => 'cannot_add_self_as_friend'
            ]);
        }

        $user = User::where('id', $request->input('userId'))->first()->append('isFriendWithMe');

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ]);
        }

        // if ($user->isFriendWithMe === 'yes' || $user->isFriendWithMe === 'pending') {
        //     return response()->json([
        //         'success' => false,
        //         'error' => 'already_friend_or_pending'
        //     ]);
        // }

        // // Create friend record
        // $new_friend = new Friend;
        // $new_friend->node_one_id = auth()->user()->id;
        // $new_friend->node_two_id = $user->id;
        // $new_friend->accepted = 0;
        // $new_friend->save();

        // Create new notification
        // $user->notify(new FriendRequest(auth()->user()));
        
        $notification = new Notification;
        $notification->id = time();
        $notification->type = "App\Notifications\FriendRequest";
        $notification->notifiable_type = "App\User";
        $notification->sender_id = auth()->user()->id;
        $notification->notifiable_id = $user->id;
        $notification->data = "";
        $notification->read_at = NULL;
        $notification->save();

        event(new FriendRequestEvent($notification, $user->id));

        return response()->json([
            'success' => true
        ]);
    }

    

    // Remove friend
    public function removeFriend(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|numeric'
        ]);

        $friend = Friend::where(function ($q) use ($request) {
            $q->where('node_one_id', $request->input('userId'))
                ->where('node_two_id', auth()->user()->id);
        })->orWhere(function ($q) use ($request) {
            $q->where('node_one_id', auth()->user()->id)
                ->where('node_two_id', $request->input('userId'));
        })->first();

        if (!$friend) {
            return response()->json([
                'success' => false,
                'error' => 'not_friends'
            ]);
        }

        $friend->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // Accept friend request
    public function updateFriendRequest(Request $request): JsonResponse
    {
        $request->validate([
            'notificationId' => 'required',
            'action' => 'required|in:accept,decline'
        ]);

        $notification = auth()->user()->notifications()->where('id', $request->input('notificationId'))->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'error' => 'notification_does_not_exist'
            ], 404);
        }

        if ($notification->hidden) {
            return response()->json([
                'success' => false,
                'error' => 'notification_already_answered'
            ], 403);
        }

        if ($notification->notifiable_id !== auth()->user()->id) {
            return resposne()->json([
                'success' => false,
                'error' => 'notification_does_not_exist_here'
            ]);
        }

        if ($notification->type !== 'App\\Notifications\\FriendRequest') {
            return response()->json([
                'success' => false,
                'error' => 'notification_type_is_not_friend_request'
            ], 403);
        }

        $notification->update(['hidden' => 1]);

        if ($request->input('action') === 'accept') {
            $friend_request = Friend::where([
                'node_one_id' => $notification->data['user_id'],
                'node_two_id' => auth()->user()->id
            ])->first();
            $friend_request->accepted = 1;
            $friend_request->save();

            $user = User::where('id', $notification->data['user_id'])->first();
            $user->notify(new FriendRequestAccepted(auth()->user()));
        } else if ($request->input('action') === 'decline') {
            Friend::where([
                'node_one_id' => $notification->data['user_id'],
                'node_two_id' => auth()->user()->id
            ])->delete();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function getNotifications(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        // auth()->user()->getCustomNotifications();

        // return response()->json([
        //     'success' => true,
        //     'payload' => auth()->user()->notifications->where('hidden', '<>', 1)
        // ]);

        return response()->json([
            'success' => true,
            'payload' => auth()->user()->getCustomNotifications()
        ]);
    }

    public function deleteNotifications(): JsonResponse
    {
        auth()->user()->notifications()->delete();

        // Delete friends requests towards this user
        Friend::where([
            'node_two_id' => auth()->user()->id,
            'accepted' => false
        ])->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function findSomeone(Request $request): JsonResponse
    {
        $request->validate([
            'plateNumber' => 'required|alpha_num'
        ]);

        $users = User::select([
            'id',
            'first_name as firstName',
            'last_name as lastName',
            'plate_number as plateNumber',
            'guest_plate AS guestPlate'
        ])
            ->where('plate_number', 'LIKE', '%' . $request->input('plateNumber') . '%')
            ->orWhere('guest_plate', 'LIKE', '%' . $request->input('plateNumber') . '%')
            ->get();

        if (!$users) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'payload' => $users
        ]);
    }

    // Get info for editing
    public function getEditInfo(): JsonResponse
    {
        $user = User::select([
            'id',
            'email',
            'plate_number AS plateNumber',
            'first_name AS firstName',
            'last_name AS lastName',
            'profile_visibility AS profileVisibility',
            'verified_driver AS verifiedDriver'
        ])
            ->where('id', auth()->user()->id)
            ->first();

        return response()->json([
            'success' => true,
            'payload' => $user
        ]);
    }

    // Edit account info
    public function editAccountInfo(Request $request): JsonResponse
    {
        // $request->validate([
        //     'email' => [
        //         'required',
        //         'email',
        //         Rule::unique('users')->ignore(auth()->user()->id)
        //     ],
        //     'firstName' => 'required|alpha',
        //     'lastName' => 'required|alpha',
        //     'profileVisibility' => 'required|in:PUBLIC,FRIENDS_ONLY'
        // ]);

        $user = User::where('id', auth()->user()->id)->first();

        $user->email = $request->input('email');
        $user->first_name = $request->input('firstName');
        $user->last_name = $request->input('lastName');
        // $user->profile_visibility = $request->input('profileVisibility');

        $user->save();

        return response()->json([
            'success' => true
        ]);

    }

    // Change password
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|min:8'
        ]);

        $user = User::where('id', auth()->user()->id)->first();

        $user->password = Hash::make($request->input('password'));

        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

    // Change profile pic
    public function changeProfilePic(Request $request): JsonResponse
    {
        $request->validate([
            'imageFile' => 'mimes:jpeg,jpg,png'
        ]);

        $user = User::where('id', auth()->user()->id)->first();

        if ($request->file('imageFile')->isValid()) {
            $upload_file = $request->file('imageFile')->store('profile_pics');
            $user->profile_pic = explode('/', $upload_file)[1];
        }

        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

    // Submit driver documentations
    public function submitDriverDocumentations(Request $request): JsonResponse
    {
        $request->validate([
            'driverLicense' => 'mimes:jpeg,jpg,png',
            'vehicleRegistration' => 'mimes:jpeg,jpg,png',
            'insuranceCard' => 'mimes:jpeg,jpg,png',
            'vehiclePicture' => 'mimes:jpeg,jpg,png',
            'driverHeadshot' => 'mimes:jpeg,jpg,png'
        ]);

        if ($request->file('driverLicense')->isValid()
            && $request->file('vehicleRegistration')->isValid()
            && $request->file('insuranceCard')->isValid()
            && $request->file('vehiclePicture')->isValid()
            && $request->file('driverHeadshot')->isValid()
        ) {
            $driver_license = $request->file('driverLicense')->store('driver_documentations');
            $vehicle_registration = $request->file('vehicleRegistration')->store('driver_documentations');
            $insurance_card = $request->file('insuranceCard')->store('driver_documentations');
            $vehicle_picture = $request->file('vehiclePicture')->store('driver_documentations');
            $driver_headshot = $request->file('driverHeadshot')->store('driver_documentations');
        }

        $new_driver_documentation = new DriverDocumentation;
        $new_driver_documentation->user_id = auth()->user()->id;
        $new_driver_documentation->driver_license = $driver_license;
        $new_driver_documentation->vehicle_registration = $vehicle_registration;
        $new_driver_documentation->insurance_card = $insurance_card;
        $new_driver_documentation->vehicle_picture = $vehicle_picture;
        $new_driver_documentation->driver_headshot = $driver_headshot;
        $new_driver_documentation->save();

        $user = User::where('id', auth()->user()->id)->first();
        $user->verified_driver = 'pending';
        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

    // Get driver documentations
    public function getDriverDocumentations(Request $request):JsonResponse{
        $documents = DriverDocumentation::select([
            'id',
            'status',
            'CREATED_AT as createdAt'
        ])
        ->where([
            'user_id' => auth()->user()->id,
        ])
        ->get();

        return response()->json([
            'success' => true,
            'payload' => $documents
        ]);
    }
    // Get plates list
    public function getPlatesList(): JsonResponse
    {
        $plates = RegisteredPlate::select([
            'id',
            'plate_number AS plateNumber'
        ])
            ->where([
                'user_id' => auth()->user()->id,
                'is_owner' => true
            ])
            ->get();

        return response()->json([
            'success' => true,
            'payload' => $plates,
            'guestPlate' => auth()->user()->guest_plate ?: false
        ]);
    }

    // Change plate number
    public function changePlateNumber(Request $request): JsonResponse
    {
        $request->validate([
            'plateNumber' => 'required|alpha_num'
        ]);

        $plate_number = RegisteredPlate::where([
            'plate_number' => trim(strtoupper($request->input('plateNumber'))),
            'is_owner' => true
        ])->first();

        if (!$plate_number) {

            $user_plate_numbers = RegisteredPlate::where([
                'user_id' => auth()->user()->id,
                'is_owner' => true
            ])->get();

            if (count($user_plate_numbers) >= 10) {
                return response()->json([
                    'success' => false,
                    'error' => 'maximum_plates'
                ]);
            }

            $new_plate_number = new RegisteredPlate;

            $new_plate_number->user_id = auth()->user()->id;
            $new_plate_number->plate_number = trim(strtoupper($request->input('plateNumber')));
            $new_plate_number->is_owner = true;
            $new_plate_number->access_code = null;

            $new_plate_number->save();

            $plate_number = $new_plate_number;
        }

        $user = User::where('id', auth()->user()->id)->first();

        if ($plate_number->user_id == auth()->user()->id) {
            $user->plate_number = $plate_number->plate_number;
            $user->save();

            return response()->json([
                'success' => true,
                'status' => 'plate_number_changed'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'verification_needed',
                'owner' => [
                    'plateNumber' => $plate_number->user->plate_number,
                    'firstName' => $plate_number->user->first_name,
                    'lastName' => $plate_number->user->last_name
                ]
            ]);
        }
    }

    // Change plate number (guest)
    public function changeGuestPlateNumber(Request $request)
    {
        $request->validate([
            'plateNumber' => 'required|alpha_num',
            'accessCode' => 'required|numeric'
        ]);

        $plate_number = RegisteredPlate::where('plate_number', trim(strtoupper($request->input('plateNumber'))))
            ->whereNotNull('access_code')
            ->first();

        if (!$plate_number) {
            return response()->json([
                'success' => false,
                'error' => 'plate_not_available'
            ]);
        }

        if ($plate_number->access_code == $request->input('accessCode')) {
            $guest_using_plate = User::where('guest_plate', trim(strtoupper($request->input('plateNumber'))))->first();

            if($guest_using_plate) {
                $guest_using_plate->guest_plate = null;
                $guest_using_plate->save();
            }

            $user = User::where('id', auth()->user()->id)->first();
            $user->guest_plate = $plate_number->plate_number;
            $user->save();

            $plate_number->access_code = null;
            $plate_number->save();

            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'wrong_access_code'
            ]);
        }
    }

    // Revoke guest plate number
    public function revokeGuestPlateNumber(Request $request): JsonResponse
    {
        $request->validate([
            'plateNumber' => 'required|alpha_num'
        ]);

        $plate_number = RegisteredPlate::where([
            'plate_number' => trim(strtoupper($request->input('plateNumber'))),
            'user_id' => auth()->user()->id,
            'is_owner' => true
        ])->first();

        if ($plate_number) {
            $guest_using_plate = User::where('guest_plate', trim(strtoupper($request->input('plateNumber'))))->first();

            $guest_using_plate->guest_plate = null;

            $guest_using_plate->save();

            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'unauthorized_access'
            ]);
        }
    }

    // Request access code
    public function requestAccessCode(Request $request): JsonResponse
    {
        $request->validate([
            'plateNumber' => 'required|alpha_num'
        ]);

        $plate_number = RegisteredPlate::where('plate_number', trim(strtoupper($request->input('plateNumber'))))->first();

        $plate_number->access_code = rand(10000, 99999);
        $plate_number->save();

        // TODO send SMS to the owner of the plate

        return response()->json([
            'success' => true
        ]);
    }

    // Delete plate
    public function deletePlateNumber(Request $request): JsonResponse
    {
        $request->validate([
            'plateNumber' => 'required|alpha_num'
        ]);

        $plate_number = RegisteredPlate::where([
            'plate_number' => trim(strtoupper($request->input('plateNumber'))),
            'user_id' => auth()->user()->id,
            'is_owner' => true
        ])->first();

        if ($plate_number) {
            if (auth()->user()->plate_number == $plate_number->plate_number) {
                return response()->json([
                    'success' => false,
                    'error' => 'cannot_delete_primary_plate'
                ]);
            }

            $plate_number->delete();

            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'unauthorized_access'
            ]);
        }
    }

    // Quit using guest plate
    public function quitUsingGuestPlate(): JsonResponse
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->guest_plate = null;
        $user->save();

        return response()->json([
            'success' => true
        ]);
    }
}
