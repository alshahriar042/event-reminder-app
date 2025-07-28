<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Mail\EventReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function dashboard()
    {
        $upcoming = Event::where('user_id', auth()->id())
            ->where('is_completed', false)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $recentCompleted = Event::where('user_id', auth()->id())
            ->where('is_completed', true)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('upcoming', 'recentCompleted'));
    }

    public function index()
    {
        $events = Event::where('user_id', auth()->id())
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reminder_minutes_before' => 'nullable|integer|min:1',
            'participants' => 'required|array|min:1',
            'participants.*' => 'required|email',
        ]);

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'reminder_minutes_before' => $validated['reminder_minutes_before'],
            'user_id' => auth()->id(),
        ]);

        foreach ($validated['participants'] as $email) {
            $event->participants()->create(['email' => $email]);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reminder_minutes_before' => 'nullable|integer|min:1',
            'participants' => 'required|array|min:1',
            'participants.*' => 'required|email',
        ]);

        $event->update($validated);

        // Sync participants
        $event->participants()->delete();
        foreach ($validated['participants'] as $email) {
            $event->participants()->create(['email' => $email]);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {

        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function upcoming()
    {
        $events = Event::where('user_id', auth()->id())
            ->where('is_completed', false)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->paginate(10);

        return view('events.upcoming', compact('events'));
    }

    public function completed()
    {
        $events = Event::where('user_id', auth()->id())
            ->where('is_completed', true)
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('events.completed', compact('events'));
    }

    public function markComplete(Event $event)
    {
        $event->update(['is_completed' => true]);
        return back()->with('success', 'Event marked as completed!');
    }

    public function sendReminder(Event $event)
    {

        foreach ($event->participants as $participant) {
            Mail::to($participant->email)->send(new EventReminder($event));
            $participant->update(['reminder_sent' => true, 'reminder_sent_at' => now()]);
        }

        return back()->with('success', 'Reminders sent successfully!');
    }
}
