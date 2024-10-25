<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('techzu.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Upcoming Events</h1>
    <ul>
        @foreach($upcomingEvents as $event)
            <li>{{ $event->title }} ({{ $event->start_time }}) - 
                <a href="{{ route('event.edit', $event->id) }}">Edit</a>
                <form action="{{ route('event.destroy', $event->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>

    <h1>Completed Events</h1>
    <ul>
        @foreach($completedEvents as $event)
            <li>{{ $event->title }} ({{ $event->start_time }})</li>
        @endforeach
    </ul>

    <a href="{{ route('event.create') }}">Create New Event</a>
    <a href="{{ route('event.import.index') }}">Import Event</a>



    <script src="{{ asset('/sw.js') }}"></script>
    <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            console.log("Service worker has been registered for scope: " + reg.scope);
        });
    }
    </script>
</body>
</html>
