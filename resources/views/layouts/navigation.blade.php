<nav class="bg-[#0d0d1a]/95 backdrop-blur-xl border-b border-indigo-500/20 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-500 p-2 rounded-xl shadow-lg shadow-purple-500/25 group-hover:shadow-purple-500/40 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent">TaskManager</span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-500/20 text-white border border-indigo-500/30' : 'text-indigo-300 hover:bg-indigo-500/20 hover:text-white border border-transparent' }}">
                    Dashboard
                </a>
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tasks.*') ? 'bg-indigo-500/20 text-white border border-indigo-500/30' : 'text-indigo-300 hover:bg-indigo-500/20 hover:text-white border border-transparent' }}">
                    My Tasks
                </a>
                <a href="{{ route('tasks.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-400 hover:to-purple-500 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-pink-500/25 transition-all duration-200 hover:shadow-pink-500/40 hover:-translate-y-0.5">
                    + New Task
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-900/30 border border-indigo-500/20">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="text-sm text-white hidden sm:block">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-indigo-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>