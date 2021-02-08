<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TaskController extends Controller
{
    public function getAllTasks(Request $request) : JsonResponse
    {
        $tasks = Task::where('activity_id', $request->input('activity_id'))
            ->where('creator_id', auth()->user()->id)->get();

        if(!$tasks)
            return response()->json([
                'success' => false,
                'error' => 'user_not_found'
            ], 404);
        else 
            return response()->json([
                'success' => true,
                'payload' => $tasks
            ]);
    }
    
    public function addTask(Request $request) : JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'activity_id' => 'required',
            'creator_id' => 'required',
            'assigned_for' => 'required',
        ]);

        $task = new Task;
        $task->activity_id = $request->input('activity_id');
        $task->title = $request->input('title');
        $task->creator_id = $request->input('creator_id');
        $task->assigned_for = $request->input('assigned_for');
        $task->status = $request->input('status');
        $task->save();

        return response()->json([
            'success' => true,
            'payload' => $task
        ]);
    }
}
