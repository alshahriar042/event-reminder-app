@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Upcoming Events</h5>
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Add Event
                </a>
            </div>
            <div class="card-body">
                @if($upcoming->isEmpty())
                    <div class="alert alert-info">No upcoming events found.</div>
                @else
                    <div class="list-group">
                        @foreach($upcoming as $event)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <h5>{{ $event->title }}</h5>
                                <span class="badge bg-primary">
                                    {{ $event->start_time->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mb-1">{{ $event->description }}</p>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt"></i>
                                {{ $event->start_time->format('M j, Y g:i A') }}
                            </small>
                            <div class="mt-2">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Event
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recently Completed</h5>
            </div>
            <div class="card-body">
                @if($recentCompleted->isEmpty())
                    <div class="alert alert-info">No recently completed events.</div>
                @else
                    <div class="list-group">
                        @foreach($recentCompleted as $event)
                        <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <small>{{ $event->updated_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted">
                                {{ $event->start_time->format('M j, Y') }}
                            </small>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
