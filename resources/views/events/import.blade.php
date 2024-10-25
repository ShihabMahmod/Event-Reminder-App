<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Event Management</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    
    <h1>Import Event</h1>
    <form id="importForm" onsubmit="handleImport(event)" enctype="multipart/form-data">
        @csrf
        <label for="file">Choose CSV File:</label>
        <input type="file" name="file" id="file" accept=".csv" required>
        <button type="submit">Import</button>
    </form>

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
            } else {
                storeEventsLocally(parsedData);
            }
        };

        reader.readAsText(file);
    }

    function parseCSV(data) {
        const rows = data.split('\n');
        const result = [];

        console.log(rows.length);

        for (let i = 1; i < rows.length; i++) { 
            const cols = rows[i].split(',');
            if (cols.length === 6) { 
                const eventData = {
                    title: cols[0].trim(),
                    ticket_price: parseFloat(cols[1].trim()),
                    description: cols[2].trim(),
                    start_time: new Date(cols[3].trim()).toISOString(),
                    end_time: new Date(cols[4].trim()).toISOString(),
                    reminder_time: new Date(cols[5].trim()).toISOString(),
                };
                result.push(eventData);
            }
        }
        return result;
    }

    async function storeEventOnServer(eventData) {
        try {
            const response = await fetch('{{ route('event.import.store') }}', {
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
            alert('Event imported successfully.');
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
</body>
</html>
