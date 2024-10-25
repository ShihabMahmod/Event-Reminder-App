<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Event</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <h1>Create Event</h1>
    <form id="event-form" onsubmit="return handleSubmit(event)">
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

    <script>
    async function handleSubmit(event) {
        event.preventDefault(); 
        const form = event.target;
        const formData = new FormData(form);
        const eventData = Object.fromEntries(formData.entries());

        if (navigator.onLine) {
            try {
                const response = await fetch('{{ route('event.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(eventData),
                });

                if (response.ok) {
                    alert('Event created successfully.');
                    form.reset();
                } else {
                    alert('Failed to create event on server.');
                }
            } catch (error) {
                console.error('Error during online event submission:', error);
            }
        } else {
            storeEventLocally(eventData);
        }
    }

    function storeEventLocally(eventData) {
        const dbRequest = indexedDB.open('event-reminder-db', 1);

        dbRequest.onupgradeneeded = function(event) {
            const db = event.target.result;
            db.createObjectStore('events', { keyPath: 'id', autoIncrement: true });
        };

        dbRequest.onsuccess = function(event) {
            const db = event.target.result;
            const transaction = db.transaction(['events'], 'readwrite');
            const store = transaction.objectStore('events');
            const addRequest = store.add(eventData);

            addRequest.onsuccess = function() {
                alert('Stored event locally! It will sync when you are online.');
            };

            addRequest.onerror = function(event) {
                console.error('Error adding event locally:', event.target.error);
            };
        };

        dbRequest.onerror = function(event) {
            console.error('Error opening IndexedDB:', event.target.error);
        };
    }

    window.addEventListener('online', syncLocalEvents);

    function syncLocalEvents() {
        const dbRequest = indexedDB.open('event-reminder-db', 1);

        dbRequest.onsuccess = function(event) {
            const db = event.target.result;
            const transaction = db.transaction(['events'], 'readonly');
            const store = transaction.objectStore('events');
            const getAllRequest = store.getAll();

            getAllRequest.onsuccess = function(event) {
                const events = event.target.result;
                events.forEach(syncEventWithServer);
            };

            getAllRequest.onerror = function(event) {
                console.error('Error retrieving events from IndexedDB:', event.target.error);
            };
        };

        dbRequest.onerror = function(event) {
            console.error('Error opening IndexedDB for syncing:', event.target.error);
        };
    }

    async function syncEventWithServer(eventData) {
        try {
            const response = await fetch('{{ route('event.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(eventData),
            });

            if (response.ok) {
                deleteEventFromIndexedDB(eventData.id);
            } else {
                console.error('Failed to sync event:', eventData);
            }
        } catch (error) {
            console.error('Error syncing event with server:', error);
        }
    }

    function deleteEventFromIndexedDB(id) {
        const dbRequest = indexedDB.open('event-reminder-db', 1);

        dbRequest.onsuccess = function(event) {
            const db = event.target.result;
            const transaction = db.transaction(['events'], 'readwrite');
            const store = transaction.objectStore('events');
            const deleteRequest = store.delete(id);

            deleteRequest.onsuccess = function() {
                console.log('Deleted synced event from IndexedDB:', id);
            };

            deleteRequest.onerror = function(event) {
                console.error('Error deleting event from IndexedDB:', event.target.error);
            };
        };

        dbRequest.onerror = function(event) {
            console.error('Error opening IndexedDB for deletion:', event.target.error);
        };
    }
</script>
</body>
</html>
