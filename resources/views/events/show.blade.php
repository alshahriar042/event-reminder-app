@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Event Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h1>{{ $event->title }}</h1>
                            <p class="text-muted">{{ $event->event_id }}</p>
                            @if ($event->description)
                                <div class="mb-3">
                                    <h6>Description:</h6>
                                    <p>{{ $event->description }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Event Time</h6>
                                    <p class="card-text">
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ $event->start_time->format('l, F j, Y') }}
                                    </p>
                                    <p class="card-text">
                                        <i class="far fa-clock me-2"></i>
                                        {{ $event->start_time->format('g:i A') }} -
                                        {{ $event->end_time->format('g:i A') }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-hourglass-half me-2"></i>
                                        @if ($event->is_completed)
                                            Completed
                                        @elseif($event->start_time->isPast())
                                            Missed
                                        @else
                                            {{ $event->start_time->diffForHumans() }}
                                        @endif
                                    </p>
                                    @if ($event->reminder_minutes_before)
                                        <p class="card-text">
                                            <i class="fas fa-bell me-2"></i>
                                            Reminder set for
                                            {{ $event->start_time->subMinutes($event->reminder_minutes_before)->format('M j, Y g:i A') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Participants</h5>
                            @if ($event->participants->isEmpty())
                                <div class="alert alert-info">No participants added to this event.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Reminder Sent</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($event->participants as $participant)
                                                @php
                                                    // Check if participant is a system user
                                                    $systemUser = App\Models\User::where(
                                                        'email',
                                                        $participant->email,
                                                    )->first();
                                                    $isSystemUser = $systemUser !== null;

                                                    // Check online status for system users
                                                    $isOnline = $isSystemUser ? $systemUser->is_online : false;

                                                    // Determine if reminder should be allowed
                                                    $allowReminder =
                                                        !$participant->reminder_sent &&
                                                        (!$isSystemUser || ($isSystemUser && !$isOnline));
                                                @endphp

                                                <tr>
                                                    <td>
                                                        {{ $participant->email }}
                                                        @if ($isSystemUser)
                                                            <span class="badge bg-info">System User</span>
                                                            <span
                                                                class="badge {{ $isOnline ? 'bg-success' : 'bg-warning' }}">
                                                                {{ $isOnline ? 'Online' : 'Offline' }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-dark">External User</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($participant->reminder_sent)
                                                            <span class="badge bg-success">Yes
                                                                ({{ $participant->reminder_sent_at->format('M j, Y g:i A') }})</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($allowReminder)
                                                            <form action="{{ route('events.remind', $event) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="email"
                                                                    value="{{ $participant->email }}">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-paper-plane"></i> Send Reminder
                                                                </button>
                                                            </form>
                                                        @elseif($isSystemUser && $isOnline)
                                                            <span class="text-muted">User is online</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            Created: {{ $event->created_at->diffForHumans() }} |
                            Last Updated: {{ $event->updated_at->diffForHumans() }}
                        </small>
                        @if (!$event->is_completed)
                            <form action="{{ route('events.complete', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
