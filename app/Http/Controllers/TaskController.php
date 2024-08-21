<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Display a listing of the tasks
    public function index()
    {
        $tasks = Task::with(['assignee', 'project'])->get();
        return response()->json($tasks, 200);
    }

    // Store a newly created task in storage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress,completed',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    // Display the specified task
    public function show($id)
    {
        $task = Task::with(['assignee', 'project'])->findOrFail($id);
        return response()->json($task, 200);
    }

    // Update the specified task in storage
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,in-progress,completed',
            'assigned_to' => 'sometimes|required|exists:users,id',
            'project_id' => 'sometimes|required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($request->all());

        return response()->json($task, 200);
    }

    // Remove the specified task from storage
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
