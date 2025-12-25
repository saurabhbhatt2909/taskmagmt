<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class TaskController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = Task::where('user_id', Auth::id());

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

            return $this->successResponse($tasks, 'Tasks retrieved successfully');

        } catch (Exception $e) {
            Log::error('Task Fetch Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,in-progress,completed',
                'due_date' => 'required|date|after_or_equal:today',
            ]);

            $task = Auth::user()->tasks()->create($validated);

            return $this->successResponse($task, 'Task created successfully', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation Failed', 422, $e->errors());

        } catch (Exception $e) {
            Log::error('Task Store Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $task = Task::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'sometimes|required|in:pending,in-progress,completed',
                'due_date' => 'sometimes|required|date',
            ]);

            $task->update($validated);

            return $this->successResponse($task, 'Task updated successfully');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found or unauthorized access', 404);

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation Failed', 422, $e->errors());

        } catch (Exception $e) {
            Log::error('Task Update Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $task = Task::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
            
            $task->delete();

            return $this->successResponse(null, 'Task deleted successfully');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Task not found or unauthorized access', 404);

        } catch (Exception $e) {
            Log::error('Task Delete Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }
}