@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Import Events from CSV</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('events.import') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                <div class="form-text">
                    <a href="{{ asset('sample_events.csv') }}" download>Download sample CSV file</a>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>
@endsection
