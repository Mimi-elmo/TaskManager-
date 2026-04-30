@extends('layouts.guest')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-xl font-bold">Welcome Back</h2>
    <p class="text-slate-400">Sign in to continue</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="block text-sm text-slate-300 mb-2">Email</label>
        <input id="email" type="email" name="email" required autofocus
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

    <div class="mb-6 flex items-center">
        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-purple-500 bg-slate-900 border-slate-700 rounded">
        <label for="remember_me" class="ml-2 text-sm text-slate-300">Remember me</label>
    </div>

    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 py-3 rounded-xl font-medium">
        Sign In
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-slate-400">Don't have an account? <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300">Register</a></p>
</div>
@endsection