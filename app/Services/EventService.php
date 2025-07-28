<?php
namespace App\Services;

use App\Models\Event;

class EventService
{
    public function create(array $data): Event
    {
        $event = Event::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'reminder_minutes_before' => $data['reminder_minutes_before'],
            'user_id' => auth()->id(),
        ]);

        $this->syncParticipants($event, $data['participants']);

        return $event;
    }

    public function update(Event $event, array $data): Event
    {
        $event->update($data);

        $event->participants()->delete();
        $this->syncParticipants($event, $data['participants']);

        return $event;
    }

    public function syncParticipants(Event $event, array $emails): void
    {
        foreach ($emails as $email) {
            $event->participants()->create(['email' => $email]);
        }
    }
}
