@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Events</h5>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Event
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Participants</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                                <br>
                                <small class="text-muted">{{ $event->event_id }}</small>
                            </td>
                            <td>{{ $event->start_time->format('M j, Y') }}</td>
                            <td>
                                {{ $event->start_time->format('g:i A') }} -
                                {{ $event->end_time->format('g:i A') }}
                            </td>
                            <td>{{ $event->participants->count() }}</td>
                            <td>
                                @if($event->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                @elseif($event->start_time->isPast())
                                    <span class="badge bg-warning text-dark">Missed</span>
                                @else
                                    <span class="badge bg-primary">Upcoming</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('events.destroy', $event) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No events found. <a href="{{ route('events.create') }}">Create your first event</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $events->links() }}
    </div>
</div>
@endsection
