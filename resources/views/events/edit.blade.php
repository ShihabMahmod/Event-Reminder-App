@extends('layout')
@section('content')
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1 class="mb-5">Edit Event</h1>
        <form id="edit-event-form" onsubmit="return handleEditSubmit(event)">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $event->title }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="4" name="description">{{ $event->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="datetime-local" class="form-control" name="start_time" id="start_time" value="{{ $event->start_time }}">
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="datetime-local" class="form-control" name="end_time" id="end_time" value="{{ $event->end_time }}">
            </div>

            <div class="mb-3">
                <label for="reminder_time" class="form-label">Reminder Time</label>
                <input type="datetime-local" class="form-control" name="reminder_time" id="reminder_time" value="{{ $event->reminder_time }}">
            </div>
            
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>

    <script>
    async function handleEditSubmit(event) {
        event.preventDefault(); 
        const form = event.target;
        const formData = new FormData(form);
        const eventData = Object.fromEntries(formData.entries());
        eventData.id = {{ $event->id }};

        if (navigator.onLine) {
            try {
                const response = await fetch(`{{ url('/event') }}/${eventData.id}`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(eventData),
                                });
                console.log(response);
                if (response.ok) {
                    const data = await response.json();
                    alert(data.success);
                    form.reset();
                } else {
                    const errorData = await response.json();
                    displayValidationErrors(errorData.errors);
                    console.log(data.errors.description);
                    alert(data.errors);
                }
            } catch (error) {
                console.error('Error during online event update:', error);
            }
        } else {
            storeEventEditLocally(eventData);
        }
    }

    function displayValidationErrors(errors) {
            
            const errorContainer = document.createElement('div');
            errorContainer.classList.add('alert', 'alert-danger'); 
            errorContainer.innerHTML = '<strong>Please fix the following errors:</strong><ul>';

            for (const [key, messages] of Object.entries(errors)) {
                messages.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorContainer.querySelector('ul').appendChild(li);
                });
            }
            errorContainer.innerHTML += '</ul>'; 
            const formContainer = document.querySelector('.container'); 
            formContainer.prepend(errorContainer);
    }

    function storeEventEditLocally(eventData) {
        const dbRequest = indexedDB.open('event-reminder-db', 1);

        dbRequest.onupgradeneeded = function(event) {
            const db = event.target.result;
            db.createObjectStore('events', { keyPath: 'id', autoIncrement: true });
        };

        dbRequest.onsuccess = function(event) {
            const db = event.target.result;
            const transaction = db.transaction(['events'], 'readwrite');
            const store = transaction.objectStore('events');
            const putRequest = store.put(eventData);

            putRequest.onsuccess = function() {
                alert('Updated event stored locally! It will sync when online.');
            };

            putRequest.onerror = function(event) {
                console.error('Error updating event locally:', event.target.error);
            };
        };

        dbRequest.onerror = function(event) {
            console.error('Error opening IndexedDB for update:', event.target.error);
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
            const response = await fetch(`{{ url('/event') }}/${eventData.id}`, {
                method: 'PUT',
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
@endsection
