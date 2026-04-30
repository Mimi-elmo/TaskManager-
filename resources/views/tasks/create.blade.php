@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('tasks.index') }}" class="text-slate-400 hover:text-white mb-4 inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back
    </a>

    <h1 class="text-2xl font-bold mb-6">Create New Task</h1>

    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-slate-300 mb-2">Title *</label>
            <input type="text" name="title" required placeholder="Task title"
                class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500 @error('title') border-red-500 @enderror"
                value="{{ old('title') }}">
            @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-2">Description</label>
            <textarea name="description" rows="3" placeholder="Task description"
                class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500 resize-none">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-slate-300 mb-2">Category *</label>
                <select name="category_id" required
                    class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500">
                    <option value="">Select</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-2">Status *</label>
                <select name="status" required
                    class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500">
                    <option value="à faire">To Do</option>
                    <option value="en cours">In Progress</option>
                    <option value="terminé">Done</option>
                </select>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 px-6 py-3 rounded-xl font-medium">Create</button>
            <a href="{{ route('tasks.index') }}" class="bg-slate-800 hover:bg-slate-700 px-6 py-3 rounded-xl font-medium border border-slate-700">Cancel</a>
        </div>
    </form>
</div>
@endsection