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
