<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Reminder: {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4299e1;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #3182ce;
        }
        .details {
            margin: 15px 0;
            padding: 10px;
            background-color: #f7fafc;
            border-left: 4px solid #4299e1;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #718096;
        }
    </style>
</head>
<body>
    <h1>Reminder: {{ $event->title }}</h1>

    <p><strong>When:</strong> {{ $event->start_time->format('l, F j, Y \a\t g:i A') }}</p>
    <p>({{ $reminderTime }} from now)</p>

    @if($event->description)
    <div class="details">
        <strong>Details:</strong><br>
        {{ $event->description }}
    </div>
    @endif

    <a href="{{ route('events.show', $event) }}" class="button">
        View Event Details
    </a>

    <div class="footer">
        <p>Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
