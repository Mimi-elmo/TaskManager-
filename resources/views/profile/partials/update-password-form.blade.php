<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
            <input type="password" 
                   class="input-apply" 
                   id="update_password_current_password" 
                   name="current_password" 
                   autocomplete="current-password">
            @error('current_password')
                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
            <input type="password" 
                   class="input-apply" 
                   id="update_password_password" 
                   name="password" 
                   autocomplete="new-password">
            @error('password')
                <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
            <input type="password" 
                   class="input-apply" 
                   id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   autocomplete="new-password">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-apply-apply bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold px-6 py-3 rounded-xl transition duration-200">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>