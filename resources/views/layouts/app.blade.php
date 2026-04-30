<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'TaskManager') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-white">
        <nav class="bg-slate-800/80 backdrop-blur border-b border-slate-700 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-xl text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    TaskManager
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tasks.index') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-lg hover:bg-slate-700">Tasks</a>
                    <a href="{{ route('tasks.create') }}" class="bg-purple-600 hover:bg-purple-500 px-4 py-2 rounded-lg font-medium">+ New</a>
                    <span class="text-slate-400 text-sm">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-400 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4">
                @yield('content')
            </div>
        </main>
    </body>
</html>