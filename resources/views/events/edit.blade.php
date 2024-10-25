<h1>Create Event</h1>
<form action="{{ route('event.update',$event->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Title</label>
    <input type="text" name="title" value="{{$event->title}}" required>

    <label>Ticket Price</label>
    <input type="number" name="ticket_price" value="{{$event->ticket_price}}" required>

    <label>Description</label>
    <textarea name="description">{{$event->description}}</textarea>

    <label>Start Time</label>
    <input type="datetime-local" value="{{$event->start_time}}" name="start_time" required>

    <label>End Time</label>
    <input type="datetime-local" value="{{$event->end_time}}" name="end_time" required>

    <label>Reminder Time</label>
    <input type="datetime-local" value="{{$event->reminder_time}}" name="reminder_time" required>

    <button type="submit">Update</button>
</form>
