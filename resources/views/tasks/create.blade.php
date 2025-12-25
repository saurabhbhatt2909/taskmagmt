<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Create Task</h2></x-slot>

    <div class="py-12 max-w-2xl mx-auto">
        <div class="bg-white p-6 shadow-sm rounded-lg">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Title</label>
                    <input type="text" name="title" class="w-full border-gray-300 rounded-md" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700">Description</label>
                    <textarea name="description" class="w-full border-gray-300 rounded-md"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-md">
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Due Date</label>
                        <input type="date" name="due_date" class="w-full border-gray-300 rounded-md" required>
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Task</button>
            </form>
        </div>
    </div>
</x-app-layout>