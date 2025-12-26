<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\Task;
use App\Mail\TaskReminderMail;
use Carbon\Carbon;

Schedule::call(function () {
    // Find tasks due tomorrow
    $tasks = Task::whereDate('due_date', Carbon::tomorrow())
                 ->where('status', '!=', 'completed')
                 ->with('user')
                 ->get();

    foreach ($tasks as $task) {
        Mail::to($task->user->email)->send(new TaskReminderMail($task));
    }
})->dailyAt('09:00');