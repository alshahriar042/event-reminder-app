@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ isset($event) ? 'Edit Event' : 'Create Event' }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ isset($event) ? route('events.update', $event) : route('events.store') }}">
                        @csrf
                        @if (isset($event))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $event->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3">{{ old('description', $event->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Start Time *</label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                                    id="start_time" name="start_time"
                                    value="{{ old('start_time', isset($event) ? $event->start_time->format('Y-m-d\TH:i') : '') }}"
                                    required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">End Time *</label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time"
                                    value="{{ old('end_time', isset($event) ? $event->end_time->format('Y-m-d\TH:i') : '') }}"
                                    required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reminder_minutes_before" class="form-label">Send Reminder Before Event</label>
                            <select class="form-select @error('reminder_minutes_before') is-invalid @enderror"
                                id="reminder_minutes_before" name="reminder_minutes_before">
                                <option value="">Don't send reminder</option>
                                <option value="5" @selected(old('reminder_minutes_before', $event->reminder_minutes_before ?? '') == 5)>5 minutes before</option>
                                <option value="15" @selected(old('reminder_minutes_before', $event->reminder_minutes_before ?? '') == 15)>15 minutes before</option>
                                <option value="30" @selected(old('reminder_minutes_before', $event->reminder_minutes_before ?? '') == 30)>30 minutes before</option>
                                <option value="60" @selected(old('reminder_minutes_before', $event->reminder_minutes_before ?? '') == 60)>1 hour before</option>
                                <option value="1440" @selected(old('reminder_minutes_before', $event->reminder_minutes_before ?? '') == 1440)>1 day before</option>
                            </select>
                            @error('reminder_minutes_before')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="participants" class="form-label">Participants *</label>
                            <select class="form-select" id="participants" name="participants[]" multiple required>
                                @php
                                    $selectedParticipants = old(
                                        'participants',
                                        isset($event) ? $event->participants->pluck('email')->toArray() : [],
                                    );

                                @endphp
                                @foreach ($selectedParticipants as $email)
                                    @php
                                        $user = $users->firstWhere('email', $email);
                                    @endphp
                                    <option value="{{ $email }}" selected>
                                        {{ $user ? "$user->name ($user->email)" : $email }}
                                    </option>
                                @endforeach

                            </select>
                            @error('participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="d-flex justify-content-between">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($event) ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#participants').select2({
                tags: true, // allows entering new values
                placeholder: "Enter or select participant emails",
            });
        });
    </script>
@endpush
@push('styles')
