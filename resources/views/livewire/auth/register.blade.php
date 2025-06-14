<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // <-- Añadir este trait

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $gender = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $default_delivery_address = '';
    public string $nif = '';
    public $photo;

    use WithFileUploads;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'gender' => ['required', 'in:F,M'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'default_delivery_address' => ['required', 'string', 'max:255'],
            'nif' => ['required', 'string', 'max:9'],
        ]);

        if ($this->photo) {
            $path = $this->photo->store('users', 'public');
            $filename = basename($path);
            $validated['photo'] = $filename;
        }

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);
        //se cambio la ruta por la de productos, antes tenia la del dashboard
        $this->redirectIntended(route('products.index', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name"
            :placeholder="__('Full name')" />

        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Email address')" type="email" required autocomplete="email"
            placeholder="email@example.com" />


        <!-- Gender -->
        <flux:select wire:model="gender" placeholder="Choose gender..." :label="__('Gender')">
            <flux:select.option value="F">Female</flux:select.option>
            <flux:select.option value="M">Male</flux:select.option>
        </flux:select>

        <!-- Password -->
        <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password"
            :placeholder="__('Password')" />

        <!-- Confirm Password -->
        <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirm password')" />


        <!-- Profile photo Address -->
        <flux:input type="file" wire:model="photo" label="Profile photo" />


        <!-- Delivery Address -->
        <flux:input wire:model="default_delivery_address" :label="__('Delivery Address')" type="text" required autofocus
            autocomplete="shipping street-address" :placeholder="__('Delivery Address')" />

        <!-- NIF -->
        <flux:input wire:model="nif" :label="__('NIF')" type="text" required autocomplete="tax-id"
            :placeholder="__('NIF')" />



        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
