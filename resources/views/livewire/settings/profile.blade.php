<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->nif = Auth::user()->nif;
        $this->photo = Auth::user()->photo;
        $this->default_delivery_address = Auth::user()->default_delivery_address;
        $this->default_payment_type = Auth::user()->default_payment_type;
        $this->default_payment_reference = Auth::user()->default_payment_reference;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'nif' => 'nullable|string|digits:9',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|in:PayPal,MB_WAY,Visa',
            'default_payment_reference' => 'nullable|string|max:255',
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            @can('update', auth()->user())
                <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />
            @else
                <flux:input wire:model="name" :label="__('Name')" type="text" disabled />
            @endcan

            @can('update', auth()->user())
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
            @else
                <flux:input wire:model="email" :label="__('Email')" type="email" disabled />
            @endcan

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <flux:text class="mt-4">
                        {{ __('Your email address is unverified.') }}
                        <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </flux:link>
                    </flux:text>

                    @if (session('status') === 'verification-link-sent')
                        <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </flux:text>
                    @endif
                </div>
            @endif

            @can('update', auth()->user())
                <flux:input wire:model="nif" :label="__('NIF')" type="text" />
            @endcan

            @can('update', auth()->user())
                <flux:input wire:model="default_delivery_address" :label="__('Default Delivery Address')" type="text" />
            @endcan

            @can('update', auth()->user())
                <flux:select wire:model="default_payment_type" :label="__('Default Payment Type')">
                    <option value="">—</option>
                    <option value="PayPal">PayPal</option>
                    <option value="MB_WAY">MB WAY</option>
                    <option value="Visa">Visa</option>
                </flux:select>
            @endcan

            @can('update', auth()->user())
                <flux:input wire:model="default_payment_reference" :label="__('Payment Reference')" type="text" />
            @endcan

            @can('update', auth()->user())
                <flux:input wire:model="photo" :label="__('Photo')" type="file" />
            @endcan

            @can('update', auth()->user())
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                    <x-action-message class="me-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
                </div>
            @endcan
        </form>

        <livewire:settings.change-password-form />

        @can('delete', auth()->user())
            <livewire:settings.delete-user-form />
        @endcan
    </x-settings.layout>
</section>
