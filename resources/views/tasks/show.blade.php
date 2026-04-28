@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">Task Details</h1>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Title</h3>
                        <p class="text-gray-900">{{ $task->title }}</p>
                    </div>

                    @if($task->description)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Description</h3>
                            <p class="text-gray-900">{{ $task->description }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Category</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $task->category->name ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Status</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($task->status === 'terminé') bg-green-100 text-green-800
                            @elseif($task->status === 'en cours') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($task->status === 'terminé') Done
                            @elseif($task->status === 'en cours') In Progress
                            @else To Do
                            @endif
                        </span>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Created</h3>
                        <p class="text-gray-900">{{ $task->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    @if($task->updated_at != $task->created_at)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Last Updated</h3>
                            <p class="text-gray-900">{{ $task->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md">
                        Edit Task
                    </a>
                    <a href="{{ route('tasks.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection