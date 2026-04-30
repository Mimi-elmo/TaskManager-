@extends('layouts.guest')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-xl font-bold">Create Account</h2>
    <p class="text-slate-400">Start managing your tasks</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-4">
        <label for="name" class="block text-sm text-slate-300 mb-2">Name</label>
        <input id="name" type="text" name="name" required autofocus
            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500 @error('name') border-red-500 @enderror"
            placeholder="John Doe" value="{{ old('name') }}">
        @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm text-slate-300 mb-2">Email</label>
        <input id="email" type="email" name="email" required
            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500 @error('email') border-red-500 @enderror"
            placeholder="you@example.com" value="{{ old('email') }}">
        @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="block text-sm text-slate-300 mb-2">Password</label>
        <input id="password" type="password" name="password" required
            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500 @error('password') border-red-500 @enderror"
            placeholder="••••••••">
        @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="mb-6">
        <label for="password_confirmation" class="block text-sm text-slate-300 mb-2">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required
            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-purple-500"
            placeholder="••••••••">
    </div>

    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 py-3 rounded-xl font-medium">
        Register
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-slate-400">Already have an account? <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300">Sign In</a></p>
</div>
@endsection