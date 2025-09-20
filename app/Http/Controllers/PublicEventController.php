<?php

namespace App\Http\Controllers;

use App\Models\Event;

class PublicEventController extends Controller
{
    public function show(Event $event)
    {
        abort_unless($event->activo, 404);
        $event->load('categories');

        return view('events.show', [
            'event' => $event,
            'categories' => $event->categories,
        ]);
    }
}
