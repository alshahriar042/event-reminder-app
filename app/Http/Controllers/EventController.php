<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Mail\EventReminder;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    protected $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

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
        $users = User::where('email', '!=', Auth::user()->email)
            ->select('name', 'email')
            ->get();
        return view('events.create', compact('users'));
    }

    public function store(StoreEventRequest $request)
    {
        $event = $this->service->create($request->validated());

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $users = User::where('email', '!=', Auth::user()->email)
            ->select('name', 'email')
            ->get();

        return view('events.create', compact('event', 'users'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->service->update($event, $request->validated());

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
