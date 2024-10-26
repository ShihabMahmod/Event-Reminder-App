Event Reminder App
    A Progressive Web Application (PWA) for managing event reminders with CRUD operations, offline functionality, scheduled email notifications via queues and cron jobs, and CSV import. This app allows users to schedule events, set reminders, and receive notifications       automatically, even when offline.

Table of Contents
    Features
    Technologies Used
    Installation
    Usage
    Offline Mode and Syncing
    Event Reminder ID Generation
    Email Reminders with Queues and Cron Jobs
    Importing Data from CSV
    Folder Structure


Features:
    CRUD Operations: Create, Read, Update, and Delete event reminders.
    Upcoming and Completed Events: Displays upcoming and completed events automatically.
    Offline Capability: Functions as a PWA, with offline data storage and automatic syncing.
    Customizable Event Reminder ID: Auto-generates unique, prefix-based IDs for each event.
    Email Notifications with Queues and Cron Jobs: Sends event reminders to external users at scheduled times using queues and cron jobs.
    CSV Import: Supports importing event data from CSV files.
    
Technologies Used

Frontend: HTML, CSS, JavaScript
    Backend: Laravel
    Database: MySQL (offline storage with IndexedDB)
    PWA: Service Workers, IndexedDB
    Email: Laravel Queue, Mail, Cron Job
    CSV Handling: Laravel Excel package

Installation:
    Prerequisites
    Ensure you have the following installed:
    PHP 8.1+
    Composer
    Node.js and npm (for frontend assets and PWA setup)
    MySQL

Steps:
    Clone the repository:
        git clone https://github.com/yourusername/event-reminder-app.git
        cd event-reminder-app
    Install Backend Dependencies:
        composer install
    Set Up Environment Variables:
        cp .env.example .env
    Serve the Application
        php artisan serve
    Queue Configuration
        php artisan queue:work
    
