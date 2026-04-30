<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="mt-6">
        <button type="button" 
                class="btn-apply-apply bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-semibold px-6 py-3 rounded-xl transition duration-200"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Delete Account') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" 
                       class="input-apply" 
                       id="password" 
                       name="password" 
                       placeholder="{{ __('Password') }}">
                @error('password')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <button type="button" 
                        class="btn-apply-apply bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition duration-200"
                        x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" 
                        class="btn-apply-apply bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-semibold px-6 py-3 rounded-xl transition duration-200">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>