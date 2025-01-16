<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="sm:sticky top-0 left-0 right-0 w-full bg-gradient-to-b from-black/90 to-black/0 pt-2">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex w-full">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('app') }}" wire:navigate>

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="48px" height="48px" fill-rule="nonzero">
                            <g fill="#fefefe" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                <g transform="scale(10.66667,10.66667)">
                                    <path d="M8.4,9.464c4.05,-1.286 4.693,-5.014 4.179,-8.357c0,0 0,-0.193 0.129,-0.129c3.921,1.929 8.292,5.979 8.292,12.215c0,4.628 -3.664,8.807 -9,8.807c-5.786,0 -9,-4.05 -9,-8.871c0,-2.893 1.929,-5.786 4.179,-7.071c0,0 0.193,0 0.193,0.129c0,0.643 0.257,2.25 0.964,3.214z"></path>
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 md:flex">
                    <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Learn') }}
                    </x-nav-link>
                    <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Safety') }}
                    </x-nav-link>
                    <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Support') }}
                    </x-nav-link>
                </div>

                <!-- Actions -->
                <div class="ml-auto items-center gap-9 hidden lg:flex">
                    <button class="font-bold text-white text-xl">
                        Language
                    </button>

                    <a class="rounded-xl bg-white px-4 py-2 flex items-center justify-center font-bold my-auto" href="{{route('login')}}">
                        Login
                    </a>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">


        <!-- Responsive Settings Options -->
        <div class="mt-3 space-y-1 flex flex-col gap-5 p-2 pb-3">
            <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Products') }}
            </x-nav-link>
            <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Learn') }}
            </x-nav-link>
            <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Safety') }}
            </x-nav-link>
            <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('app')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Support') }}
            </x-nav-link>

        </div>
    </div>
</nav>