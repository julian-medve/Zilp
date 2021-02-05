<?php

namespace App\Http\Controllers;

use App\ChatSession;
use App\Message;
use App\Notifications\CallRequest;
use App\Notifications\NewMessage;
use App\User;
use App\Events\SendMessageEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ChatController extends Controller
{
    // Get messages
    public function getChat(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|numeric'
        ]);

        $user = User::where('id', $request->input('userId'))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        }

        $chat_session = ChatSession::where(function ($q) use ($request) {
            $q->where('node_one_user_id', $request->input('userId'))
                ->where('node_two_user_id', auth()->user()->id)
                ->orWhere(function($query) use ($request) {
                    $query->where('node_one_user_id', auth()->user()->id)
                          ->where('node_two_user_id', $request->input('userId'));
                }); 
            })->first();

        if (!$chat_session) {
            $new_chat_session = new ChatSession;
            $new_chat_session->node_one_user_id = auth()->user()->id;
            $new_chat_session->node_two_user_id = $request->input('userId');
            $new_chat_session->save();

            $chat_session_id = $new_chat_session->id;
        } else {
            $chat_session_id = $chat_session->id;
        }

        $messages = Message::select([
            'id',
            'from_user_id AS userId',
            'to_user_id AS toUserId',
            'message_type AS messageType',
            'message_content AS text'
        ])
        ->where('chat_session_id', $chat_session_id)
        ->get();

        foreach($messages as $key => $message)
        {
            if($message->userId == auth()->user()->id){
                $message->me = true;
            }else{
                $message->me = false;
            }
        }

        // Mark as read
        Message::where([
            'from_user_id' => $request->input('userId'),
            'to_user_id' => auth()->user()->id,
            'chat_session_id' => $chat_session_id
        ])
        ->update(['read_at' => Carbon::now()->toDateTimeString()]);

        return response()->json([
            'success' => true,
            'payload' => $messages
        ]);
    }

    public static function sendSMS($post_body, $url, $username, $password)
    {
        $ch = curl_init();
        $headers = array(
            'Content-Type:application/json',
            'Authorization:Basic ' . base64_encode("$username:$password")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        // Allow cUrl functions 20 seconds to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // Wait 10 seconds while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $output = array();
        $output['server_response'] = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $output['http_status'] = $curl_info['http_code'];
        $output['error'] = curl_error($ch);
        curl_close($ch);
        return $output;
    }

    public function getUsers(Request $request) : JsonResponse
    {
        $users = User::select([
            'id',
            'first_name AS firstName',
            'last_name AS lastName',
            'plate_number AS plateNumber',
            'email',
            'phone'
        ])->get();

        foreach($users as $index => $user){
            // if($user->id == auth()->user()->id){
            //     unset($users[$index]);
            //     continue;
            // }   

            $user->name = $user->firstName . ' ' . $user->lastName;
            $user->isPrivate = true;
        }

        return response()->json([
            'success' => true,
            'payload' => $users
        ]);
    }

    // Send a message
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|numeric',
            'message' => 'required',
            'messageType' => 'required|in:TEXT,IMAGE,VIDEO,VOICE,FILE,LOCATION'
        ]);

        // Check if the target is not as same as the sender
        if ($request->input('userId') === auth()->user()->id) {
            return response()->json([
                'success' => false,
                'error' => 'defined_self_as_target'
            ], 422);
        }

        // Check if user exists
        $user = User::where('id', $request->input('userId'))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        }

        // Check if session exists
        $chat_session = ChatSession::where(function ($q) use ($request) {
            $q->where('node_one_user_id', $request->input('userId'))
                ->where('node_two_user_id', auth()->user()->id)
                ->orWhere(function($query) use ($request) {
                    $query->where('node_one_user_id', auth()->user()->id)
                          ->where('node_two_user_id', $request->input('userId'));
                }); 
            })->first();

        if (!$chat_session) {
            return response()->json([
                'success' => false,
                'error' => 'chat_session_does_not_exist'
            ]);
        }

        // Send message
        $new_message = new Message;
        $new_message->chat_session_id = $chat_session->id;
        $new_message->from_user_id = auth()->user()->id;
        $new_message->to_user_id = $user->id;
        $new_message->message_type = $request->input('messageType');
        $new_message->message_content = strip_tags($request->input('message'));
        $new_message->save();

        // Send to pusher
        $get_current_message = Message::select([
            'id',
            'from_user_id AS userId',
            'to_user_id AS toUserId',
            'message_type AS messageType',
            'message_content AS text'
        ])
        ->where('id', $new_message->id)
        ->first();


        event(new SendMessageEvent($get_current_message, $user->id));


        // Send SMS if user is offline
        if(!$user->isOnline) {
            $messages = [
                [
                    'to' => '+' . substr($user->phone, 2),
                    'body' => 'You have a new message from '
                        . auth()->user()->first_name
                        . ' '
                        . auth()->user()->last_name
                        . ' '
                        . '(' . auth()->user()->plate_number . ')'
                        . ' -  Zilp'
                ]
            ];

            self::sendSMS(json_encode($messages),
                'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30',
                env('BULKSMS_USERNAME'),
                env('BULKSMS_PASSWORD'));
        }

        // Notify user itself
        // auth()->user()->notify(new NewMessage($get_current_message, auth()->user()->id));

        return response()->json([
            'success' => true,
            'payload' => $get_current_message
        ]);
    }

    // Upload voice
    public function uploadVoice(Request $request): JsonResponse
    {
        $request->validate([
            'voiceFile' => 'required|mimes:webm|max:5000'
        ]);

        if ($request->file('voiceFile')->isValid()) {
            $upload_file = $request->file('voiceFile')->store('voice');
        }

        return response()->json([
            'success' => true,
            'payload' => explode('/', $upload_file)[1]
        ]);
    }

    // Get voice file
    public function getVoiceFile($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/voice/' . $file_name));
    }

    // Upload image
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'imageFile' => 'required|mimes:jpeg,jpg,png|max:20000'
        ]);

        if ($request->file('imageFile')->isValid()) {
            $upload_file = $request->file('imageFile')->store('chat_images');
        }

        return response()->json([
            'success' => true,
            'payload' => explode('/', $upload_file)[1]
        ]);
    }

    // Get chat image file
    public function getImageFile($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/chat_images/' . $file_name));
    }

    // Upload video
    public function uploadVideo(Request $request): JsonResponse
    {
        $request->validate([
            'videoFile' => 'required|mimes:webm,mpg,ogg,mp4,flv,avi,wmv,mkv|max:20000'
        ]);

        if ($request->file('videoFile')->isValid()) {
            $upload_file = $request->file('videoFile')->store('chat_videos');
        }

        return response()->json([
            'success' => true,
            'payload' => explode('/', $upload_file)[1]
        ]);
    }

    // Get video file
    public function getVideoFile($file_name): BinaryFileResponse
    {
        return response()->file(storage_path('app/chat_videos/' . $file_name));
    }

    // Mark as read
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|numeric'
        ]);

        $user = User::where('id', $request->input('userId'))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        }

        $chat_session = ChatSession::where(function ($q) use ($request) {
            $q->where('node_one_user_id', $request->input('userId'))
                ->orWhere('node_two_user_id', $request->input('userId'));
        })->first();

        if (!$chat_session) {
            return response()->json([
                'success' => false,
                'error' => 'chat_session_not_found'
            ]);
        }

        // Mark as read
        Message::where([
            'from_user_id' => $request->input('userId'),
            'to_user_id' => auth()->user()->id,
            'chat_session_id' => $chat_session->id
        ])
            ->update(['read_at' => Carbon::now()->toDateTimeString()]);

        return response()->json([
            'success' => true
        ]);
    }

    // Call request
    public function callRequest(Request $request): JsonResponse
    {
        $request->validate([
            'userId' => 'required|numeric'
        ]);

        $user = User::where('id', $request->input('userId'))->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        }

        if (!$user->isFriend(auth()->user()->id)) {
            return response()->json([
                'success' => false,
                'error' => 'not_friends'
            ], 403);
        }

        $user->notify(new CallRequest(auth()->user()));

        return response()->json([
            'success' => true
        ]);
    }
}
