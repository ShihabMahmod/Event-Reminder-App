
@extends('layout')
@section('content')
    <div class="container mt-5">
        <h1 class="mb-5">Import Reminder Event</h1>
        <form id="importForm" onsubmit="handleImport(event)" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="formFile" class="form-label">Choose CSV File:</label>
                <input class="form-control" type="file" name="file" id="file" accept=".csv" required>
            </div>

            <button class="btn btn-primary" type="submit">Import</button>
        </form>
    </div>

    <script>
    async function handleImport(event) {
        event.preventDefault();
        
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];
        if (!file) {
            alert('Please select a CSV file.');
            return;
        }

        const reader = new FileReader();
        reader.onload = async function(event) {
            const csvData = event.target.result;
            const parsedData = parseCSV(csvData);
            
            if (navigator.onLine) {
                for (const eventData of parsedData) {
                    await storeEventOnServer(eventData);
                }
                alert('Event imported successfully.');
            } else {
                storeEventsLocally(parsedData);
            }
        };

        reader.readAsText(file);
    }

    function parseCSV(data) {
        const rows = data.split('\n');
        const result = [];

        for (let i = 1; i < rows.length; i++) { 
           
            const cols = rows[i].split(',');
            console.log(cols);
            if (cols.length === 5) { 
                const eventData = {
                    title: cols[0].trim(),
                    description: cols[1].trim(),
                    start_time: new Date(cols[2].trim()).toISOString(),
                    end_time: new Date(cols[3].trim()).toISOString(),
                    reminder_time: new Date(cols[4].trim()).toISOString(),
                };
                result.push(eventData);
            }
        }
        return result;
        console.log(eventData);
    }

    async function storeEventOnServer(eventData) {
        try {
            const response = await fetch('{{ route('event.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(eventData),
            });
            
            if (!response.ok) {
                throw new Error('Failed to store event on server');
            }
            
        } catch (error) {
            console.error('Error storing event on server:', error);
        }
    }

    function storeEventsLocally(events) {
        const dbRequest = indexedDB.open('event-reminder-db', 1);

        dbRequest.onupgradeneeded = function(event) {
            const db = event.target.result;
            db.createObjectStore('events', { keyPath: 'id', autoIncrement: true });
        };

        dbRequest.onsuccess = function(event) {
            const db = event.target.result;
            const transaction = db.transaction(['events'], 'readwrite');
            const store = transaction.objectStore('events');

            events.forEach(eventData => {
                store.add(eventData);
            });

            alert('Stored events locally! They will sync when you are online.');
        };

        dbRequest.onerror = function(event) {
            console.error('Error storing events locally:', event.target.error);
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

            getAllRequest.onsuccess = async function(event) {
                const events = event.target.result;
                for (const eventData of events) {
                    const success = await storeEventOnServer(eventData);
                    if (success) {
                        deleteEventFromIndexedDB(eventData.id);
                    }
                }
            };
        };
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
    }
    </script>

@endsection