Event Reminder App
A Progressive Web Application (PWA) for managing events and reminders with CRUD functionality, offline support, scheduled email reminders via cron jobs, and CSV import. This app is designed to handle event scheduling, notifications, and reminders, even when offline, and syncs data once an internet connection is re-established.

Table of Contents
Features
Technologies Used
Installation
Usage
Offline Mode and Syncing
Event Reminder ID Generation
Email Reminders with Cron Job
Importing Data from CSV
Folder Structure
License
Features
CRUD Operations: Full Create, Read, Update, Delete functionality for event reminders.
Upcoming and Completed Events: Automatically tracks and displays upcoming and completed events.
Offline Capability: Uses Progressive Web App (PWA) standards to store data offline and sync when back online.
Customizable Event Reminder ID: Event reminders have unique, predefined prefix-based IDs.
Email Notifications via Cron Job: Sends event reminder emails to external users at specified times using a cron job.
CSV Import: Import event data from CSV files for quick setup and data migration.
Technologies Used
Frontend: HTML, CSS, JavaScript, Vue.js (or React if used)
Backend: Laravel
Database: MySQL (with offline storage using IndexedDB or similar)
PWA: Service Workers, Cache API, IndexedDB
Email: Laravel Notifications, Mail, Cron Job
CSV Handling: Laravel Excel package
Installation
Prerequisites
PHP 8.1 or above
Composer
Node.js and npm (for frontend assets and PWA setup)
MySQL
Steps
Clone the repository:

bash
Copy code
git clone https://github.com/yourusername/event-reminder-app.git
cd event-reminder-app
Install Backend Dependencies:

bash
Copy code
composer install
Install Frontend Dependencies:

bash
Copy code
npm install && npm run dev
Configure Environment Variables:

Copy .env.example to .env and update the necessary fields (database credentials, mail configuration, etc.):

bash
Copy code
cp .env.example .env
php artisan key:generate
Run Migrations and Seeders:

bash
Copy code
php artisan migrate --seed
Serve the Application:

bash
Copy code
php artisan serve
Set up PWA:

Register the Service Worker and ensure manifest.json is configured correctly to provide PWA capabilities.

Set Up Queue (for Email Notifications):

Make sure the queue is running for email notifications:

bash
Copy code
php artisan queue:work
Set Up the Cron Job for Email Reminders:

To automate email reminders, set up a cron job to run Laravel's schedule:run command every minute.

Example (Linux/MacOS):

Open the crontab editor:

bash
Copy code
crontab -e
Add the following line to the crontab to schedule it every minute:

bash
Copy code
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
Replace /path-to-your-project/ with the absolute path to your Laravel project directory.

Usage
Create Event Reminder
Navigate to the Create Event section.
Fill out event details, including title, description, reminder time, start and end time.
Submit to save and automatically generate a unique ID with a predefined prefix.
View Upcoming and Completed Events
The app separates Upcoming and Completed events automatically based on the current date and time.
Use the filter options on the event list to view upcoming or completed events.
Manage Offline Data
The app allows you to add, edit, and delete events while offline.
Once online, the app will automatically sync offline data to the server.
Offline Mode and Syncing
This application is built as a Progressive Web App (PWA) and uses the following technologies for offline functionality:

Service Worker: Caches static assets and pages for offline access.
IndexedDB: Stores event data locally when offline.
Sync Logic: Once the device is back online, offline events are synced with the server.
Event Reminder ID Generation
Each event is assigned a unique ID with a specific prefix format. This format is configured in the backend and automatically applied upon event creation.

Email Reminders with Cron Job
Automated Reminders: Email reminders are scheduled based on the event’s specified reminder_time field.
Cron Job: The cron job runs every minute and triggers Laravel’s schedule:run command to check for and send reminder emails.
Configuration: Ensure the mail settings in .env are correct to enable email notifications.
Setting Up the Scheduler in Laravel
In App\Console\Kernel.php, schedule the email reminder job:

php
Copy code
protected function schedule(Schedule $schedule)
{
    $schedule->command('reminders:send')->everyMinute();
}
This will send reminder emails to users outside of the system at the specified reminder_time.

Importing Data from CSV
To import data:

Go to the Import CSV section.
Upload a CSV file containing event data.
Ensure the CSV follows the required format:
title, description, reminder_time, start_time, end_time, recipients
The app will validate and import events, creating or updating existing entries as needed.
