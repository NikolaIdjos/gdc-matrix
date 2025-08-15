<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('EventsPage'));
Route::get('/events/{id}', fn ($id) => Inertia::render('EventDetailsPage', [
    'event_id' => $id,
]));
