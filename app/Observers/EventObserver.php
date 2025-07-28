<?php

namespace App\Observers;

use App\Models\Event;

class EventObserver
{
    /**
     * Handle the EventReminder "creating" event.
     */
    public function creating(Event $event)
    {
        do {
            $last = Event::orderBy('id', 'desc')->first();
            $number = $last ? ((int) str_replace('EVT-', '', $last->event_id)) + 1 : 1;
            $generatedId = 'EVT-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        } while (Event::where('event_id', $generatedId)->exists());

        $event->event_id = $generatedId;
    }
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
