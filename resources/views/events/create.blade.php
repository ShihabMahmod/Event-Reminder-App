<h1>Create Event</h1>
<form action="{{ route('event.store') }}" method="POST">
    @csrf
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Ticket Price</label>
    <input type="number" name="ticket_price" required>

    <label>Description</label>
    <textarea name="description"></textarea>

    <label>Start Time</label>
    <input type="datetime-local" name="start_time" required>

    <label>End Time</label>
    <input type="datetime-local" name="end_time" required>

    <label>Reminder Time</label>
    <input type="datetime-local" name="reminder_time" required>
    <button type="submit">Save</button>
</form>
