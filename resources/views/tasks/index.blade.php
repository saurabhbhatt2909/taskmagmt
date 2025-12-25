<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4 flex justify-between">
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + New Task
                </a>
                
                <form method="GET" action="{{ route('tasks.index') }}" class="flex gap-2">
                    <select name="status" class="rounded-md border-gray-300">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Filter</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-2 border-b">Title</th>
                                <th class="p-2 border-b">Status</th>
                                <th class="p-2 border-b">Due Date</th>
                                <th class="p-2 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 border-b">
                                    <div class="font-bold">{{ $task->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                </td>
                                <td class="p-2 border-b">
                                    <span class="px-2 py-1 text-xs rounded 
                                        {{ $task->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($task->status == 'in-progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td class="p-2 border-b">{{ $task->due_date->format('M d, Y') }}</td>
                                <td class="p-2 border-b">
                                    <div class="flex items-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('tasks.edit', $task) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                            Edit
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('tasks.destroy', $task) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete task?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>