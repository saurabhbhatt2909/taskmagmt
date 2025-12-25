<h1>Hello, {{ $task->user->name }}</h1>
<p>This is a reminder that your task <strong>{{ $task->title }}</strong> is due tomorrow ({{ $task->due_date->format('Y-m-d') }}).</p>