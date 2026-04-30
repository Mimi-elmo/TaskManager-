@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">My Tasks</h1>
            <p class="text-slate-400">{{ $tasks->total() }} tasks</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="bg-purple-600 hover:bg-purple-500 px-5 py-2.5 rounded-xl font-medium inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Task
        </a>
    </div>

    @if($tasks->isEmpty())
        <div class="bg-slate-800 rounded-2xl p-12 text-center border border-slate-700">
            <svg class="w-12 h-12 text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <h3 class="text-xl font-semibold mb-2">No tasks yet</h3>
            <p class="text-slate-400 mb-4">Create your first task to get started</p>
            <a href="{{ route('tasks.create') }}" class="bg-purple-600 hover:bg-purple-500 px-5 py-2.5 rounded-xl font-medium inline-block">Create Task</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($tasks as $task)
                <div class="bg-slate-800 rounded-xl p-4 border border-slate-700 hover:border-purple-500/50 flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-lg {{ $task->status === 'terminé' ? 'line-through text-slate-500' : '' }}">{{ $task->title }}</h3>
                        @if($task->description)
                            <p class="text-slate-400 text-sm mt-1 truncate">{{ $task->description }}</p>
                        @endif
                        <div class="flex gap-2 mt-2">
                            <span class="bg-purple-500/20 text-purple-300 text-xs px-2 py-1 rounded-full">{{ $task->category?->name ?? 'Uncategorized' }}</span>
                            <span class="text-xs px-2 py-1 rounded-full 
                                {{ $task->status === 'terminé' ? 'bg-emerald-500/20 text-emerald-300' : 
                                   ($task->status === 'en cours' ? 'bg-amber-500/20 text-amber-300' : 'bg-rose-500/20 text-rose-300') }}">
                                {{ $task->status === 'terminé' ? 'Done' : ($task->status === 'en cours' ? 'In Progress' : 'To Do') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded-lg text-sm">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-rose-500/20 hover:bg-rose-500 text-rose-300 px-3 py-2 rounded-lg text-sm" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($tasks->hasPages())
            <div class="flex justify-center">{{ $tasks->links() }}</div>
        @endif
    @endif
</div>
@endsection