<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable
    class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
>
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    {{-- Logo / Inicio --}}
    <a href="{{ route('home') }}" class="mb-6 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo />
    </a>

@if(auth()->user()?->photo)
    <div class="mb-6 flex justify-center">
        <img
            src="{{auth()->user()->image_url}}"
            alt="Imagen de {{ auth()->user()?->firstLastInitial() }}"
            class="h-24 w-24 object-cover rounded-full border-2 border-zinc-300 dark:border-zinc-600"
        >
    </div>
@endif


    {{-- Catálogo disponible para todos --}}
    <flux:navlist variant="outline">
        <flux:navlist.group heading="Catálogo">
            <flux:navlist.item
                icon="tag"
                :href="route('products.index')"
                :current="request()->routeIs('products.index')"
                wire:navigate
            >Productos</flux:navlist.item>
            <flux:navlist.item
                icon="shopping-cart"
                :href="route('cart.show')"
                :current="request()->routeIs('cart.show')"
                wire:navigate
            >Carrito</flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>
@auth

@can('viewAny', App\Models\Order::class)
  <flux:navlist variant="outline">
    <flux:navlist.group heading="Pedidos">
      <flux:navlist.item
        icon="academic-cap"
        :href="route('orders.pending')"
        :current="request()->routeIs('orders.pending')"
        wire:navigate
      >Pendientes</flux:navlist.item>
    </flux:navlist.group>
  </flux:navlist>
@endcan

@can('viewAny', App\Models\Product::class)
  <flux:navlist variant="outline">
    <flux:navlist.group heading="Inventario">
      <flux:navlist.item
        icon="archive-box"
        :href="route('inventory.index')"
        :current="request()->routeIs('inventory.index')"
        wire:navigate
      >Ver inventario</flux:navlist.item>
    </flux:navlist.group>
  </flux:navlist>
@endcan
        {{-- Órdenes de suministro (empleados & board) --}}
        @can('viewAny', App\Models\SupplyOrder::class)
        <flux:navlist variant="outline">
            <flux:navlist.group heading="Reposición">
                <flux:navlist.item
                    icon="truck"
                    :href="route('supply-orders.index')"
                    :current="Str::startsWith(request()->route()->getName(), 'supply-orders.')"
                    wire:navigate
                >Órdenes de suministro</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>
        @endcan

        {{-- Administración de plataforma (solo board) --}}
        @can('viewAny', App\Models\User::class)
        <flux:navlist variant="outline">
            <flux:navlist.group heading="Administración">
                <flux:navlist.item
                    icon="users"
                    :href="route('users.index')"
                    :current="request()->routeIs('users.*')"
                    wire:navigate
                >Usuarios</flux:navlist.item>
                <flux:navlist.item
                    icon="folder-minus"
                    :href="route('categories.index')"
                    :current="request()->routeIs('categories.*')"
                    wire:navigate
                >Categorías</flux:navlist.item>
                <flux:navlist.item
                    icon="shopping-bag"
                    :href="route('products.index')"
                    :current="request()->routeIs('products.*')"
                    wire:navigate
                >Productos</flux:navlist.item>
                
             
                <flux:navlist.item
                    icon="chart-bar"
                    :href="route('stats.global')"
                    :current="request()->routeIs('stats.global')"
                    wire:navigate
                >Estadísticas</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>
        @endcan

        {{-- Menú usuario autenticado al pie --}}
        <flux:spacer/>
        <flux:dropdown position="bottom" align="start">
            <flux:profile
                :name="auth()->user()?->firstLastName()"
                :initials="auth()->user()?->firstLastInitial()"
                icon-trailing="chevrons-up-down"
            />
            <flux:menu class="w-[220px]">
                <div class="p-2 text-sm">
                    <span class="font-semibold">{{ auth()->user()->name }}</span><br>
                    <span class="text-xs">{{ auth()->user()->email }}</span>
                </div>
                <flux:menu.separator/>
                <flux:menu.item
                    :href="route('settings.profile')"
                    icon="cog"
                    wire:navigate
                >Settings</flux:menu.item>
                <flux:menu.separator/>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                    >Log Out</flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    @else
        {{-- Enlaces de login para invitados --}}
        <flux:spacer/>
        <flux:navlist variant="outline">
            <flux:navlist.group heading="Authentication">
                <flux:navlist.item
                    icon="key"
                    :href="route('login')"
                    :current="request()->routeIs('login')"
                    wire:navigate
                >Login</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>
    @endauth
</flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()?->firstLastInitial()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()?->firstLastInitial()}}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()?->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()?->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @else
                <flux:navbar>
                    <flux:navbar.item  icon="key" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>Login</flux:navbar.item>
                </flux:navbar>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
