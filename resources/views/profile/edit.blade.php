<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-6 sm:px-8 space-y-8">
            <div class="glass-apply rounded-2xl overflow-hidden">
                <div class="card-header-apply bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Profile Information</h3>
                    <p class="text-indigo-100 text-sm">Update your account details</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-apply rounded-2xl overflow-hidden">
                <div class="card-header-apply bg-gradient-to-br from-amber-500 via-orange-600 to-red-500 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Update Password</h3>
                    <p class="text-amber-100 text-sm">Ensure your account is secure</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="glass-apply rounded-2xl overflow-hidden">
                <div class="card-header-apply bg-gradient-to-br from-rose-500 via-pink-600 to-red-500 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Delete Account</h3>
                    <p class="text-rose-100 text-sm">Permanently remove your account</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>