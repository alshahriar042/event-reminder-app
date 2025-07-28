
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">@yield('title')</h5>
    </div>
    <div class="card-body">
        @if($events->isEmpty())
            <div class="alert alert-info">No events found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
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
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $events->links() }}
        @endif
    </div>
</div>
@endsection

@section('title', 'Upcoming Events')  <!-- For upcoming.blade.php -->
<!-- OR -->
@section('title', 'Completed Events') <!-- For completed.blade.php -->
