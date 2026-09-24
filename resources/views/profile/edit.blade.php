<x-layouts.app title="Profile">
    <div>
        <x-ui.page-header title="Profile" subtitle="Manage your account information and security." />

        <div class="max-w-xl space-y-6">
            <x-ui.card class="mb-6">
                @include('profile.partials.update-profile-information-form')
            </x-ui.card>

            <x-ui.card class="mb-6">
                @include('profile.partials.update-password-form')
            </x-ui.card>

            <x-ui.card class="mb-6">
                @include('profile.partials.delete-user-form')
            </x-ui.card>
        </div>
    </div>
</x-layouts.app>
