@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-6">
        <h1 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-purple-100 mt-1">Manage your tasks efficiently</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('tasks.index') }}" class="bg-slate-800 rounded-xl p-4 border border-slate-700 hover:border-purple-500">
            <p class="text-slate-400 text-sm">Total</p>
            <p class="text-2xl font-bold">{{ \App\Models\Task::where('user_id', auth()->id())->count() }}</p>
        </a>
        <a href="{{ route('tasks.index') }}?status=à%20faire" class="bg-slate-800 rounded-xl p-4 border border-slate-700 hover:border-rose-500">
            <p class="text-rose-400 text-sm">To Do</p>
            <p class="text-2xl font-bold">{{ \App\Models\Task::where('user_id', auth()->id())->where('status', 'à faire')->count() }}</p>
        </a>
        <a href="{{ route('tasks.index') }}?status=en%20cours" class="bg-slate-800 rounded-xl p-4 border border-slate-700 hover:border-amber-500">
            <p class="text-amber-400 text-sm">In Progress</p>
            <p class="text-2xl font-bold">{{ \App\Models\Task::where('user_id', auth()->id())->where('status', 'en cours')->count() }}</p>
        </a>
        <a href="{{ route('tasks.index') }}?status=terminé" class="bg-slate-800 rounded-xl p-4 border border-slate-700 hover:border-emerald-500">
            <p class="text-emerald-400 text-sm">Done</p>
            <p class="text-2xl font-bold">{{ \App\Models\Task::where('user_id', auth()->id())->where('status', 'terminé')->count() }}</p>
        </a>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('tasks.create') }}" class="bg-purple-600 hover:bg-purple-500 px-6 py-3 rounded-xl font-medium">+ New Task</a>
        <a href="{{ route('tasks.index') }}" class="bg-slate-800 hover:bg-slate-700 px-6 py-3 rounded-xl font-medium border border-slate-700">View All Tasks</a>
    </div>
</div>
@endsection