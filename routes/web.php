<?php

use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Livewire\Home;
use App\Livewire\Swiper\Swiper;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::middleware(['web','auth'])->get('/swipe',Swiper::class);

Route::get('/app',Home::class)->middleware(['auth'])->name('app');
Route::get('/app/chat',Index::class)->middleware(['auth'])->name('chat.index');
Route::get('/app/chat/{chat}',Chat::class)->middleware(['auth'])->name('chat');


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
