@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-indigo-50 to-blue-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-bold mb-8">
            ← Back to Tasks
        </a>

        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            
            <!-- Header with Status -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $task->title }}</h1>
                        <p class="text-indigo-100">Created on {{ $task->created_at->format('F d, Y \a\t H:i') }}</p>
                    </div>
                    <div>
                        @if ($task->status === 'terminé')
                            <span class="bg-green-500 text-white text-lg px-6 py-3 rounded-full font-bold">✅ Done</span>
                        @elseif ($task->status === 'en cours')
                            <span class="bg-yellow-500 text-white text-lg px-6 py-3 rounded-full font-bold">🟡 In Progress</span>
                        @else
                            <span class="bg-red-500 text-white text-lg px-6 py-3 rounded-full font-bold">🔴 To Do</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                
                <!-- Description -->
                @if($task->description)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                        <div class="bg-gray-50 rounded-lg p-6 border-l-4 border-indigo-600">
                            <p class="text-gray-700 leading-relaxed">{{ $task->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    
                    <!-- Category -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide mb-2">Category</h3>
                        <div class="bg-blue-100 text-blue-800 inline-block px-6 py-3 rounded-lg font-bold">
                            {{ $task->category->name }}
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide mb-2">Status</h3>
                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <select name="status" class="px-6 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-600 font-bold" onchange="this.form.submit()">
                                <option value="à faire" {{ $task->status === 'à faire' ? 'selected' : '' }}>🔴 To Do</option>
                                <option value="en cours" {{ $task->status === 'en cours' ? 'selected' : '' }}>🟡 In Progress</option>
                                <option value="terminé" {{ $task->status === 'terminé' ? 'selected' : '' }}>✅ Done</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-bold text-gray-600 uppercase">Created</p>
                            <p class="text-gray-900 font-bold">{{ $task->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-600 uppercase">Last Updated</p>
                            <p class="text-gray-900 font-bold">{{ $task->updated_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-600 uppercase">Task ID</p>
                            <p class="text-gray-900 font-bold">#{{ $task->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <a href="{{ route('tasks.edit', $task) }}" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 rounded-lg shadow-lg transition transform hover:scale-105 text-center">
                        ✏️ Edit Task
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-lg shadow-lg transition transform hover:scale-105" onclick="return confirm('Are you sure you want to delete this task?')">
                            🗑️ Delete Task
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Back to Tasks -->
        <div class="mt-8 text-center">
            <a href="{{ route('tasks.index') }}" class="inline-block bg-white hover:bg-gray-50 text-gray-700 font-bold py-3 px-8 rounded-lg shadow-lg transition">
                ← Back to My Tasks
            </a>
        </div>

    </div>
</div>
@endsection