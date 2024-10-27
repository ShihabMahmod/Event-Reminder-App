
# Event Reminder App

A Progressive Web Application (PWA) for managing event reminders with CRUD operations, offline functionality, scheduled email notifications via queues and cron jobs, and CSV import. This app allows users to schedule events, set reminders, and receive email notifications automatically, even when offline.




## Table of Contents Feature

- Technologies Used Installation
- Usage Offline Mode and Syncing
- Event Reminder ID Generation
- Email Reminders with Queues and Cron Jobs
- Importing Data from CSV Folder Structure




## Features:

This project is used by the following companies:

- CRUD Operations: Create, Read, Update, and Delete event reminders
- Upcoming and Completed Events: Displays upcoming and completed events automatically
- Offline Capability: Functions as a PWA, with offline data storage and automatic syncing
-  Customizable Event Reminder ID: Auto-generates unique, prefix-based IDs for each event
- Email Notifications with Queues and Cron Jobs: Sends event reminders to external users at scheduled times using queues and cron jobs
- CSV Import: Supports importing event data from CSV files


## Technologies Used



**Client:** HTML, CSS, JavaScrip

**Server:** Laravel Database: MySQL (offline storage with IndexedDB)

**PWA:** Service Workers, IndexedDB

**Email:** Laravel Queue, Mail, Cron Job

**CSV Handling:**  Laravel Excel package


## Installation Requirement

Prerequisites Ensure you have the following installed

- PHP 8.1+

- Composer 

- Node.js

- MySQL


## Installation

Steps 1 : Clone the repository

```bash
  git clone https://github.com/yourusername/event-reminder-app.git

```


Steps 2 : Goto Project

```bash
  cd event-reminder-app

```

Steps 3 : Set Up Environment Variables

```bash
  cp .env.example .env

```


Steps 4 : php artisan serve

```bash
  cp .env.example .env

```

Steps 4 : Queue Configuration

```bash
  php artisan queue:work

```

    
## Usage

**Create Event Reminder:**

- Navigate to the Create Event section.
- Fill out the event details, including title, description, reminder time, start, and end times.

- Submit to save, and a unique ID with a predefined prefix will be generated automatically.


**View Upcoming and Completed Events**

- The app automatically categorizes events as Upcoming or Completed based on the current   date and time.

- Use filters in the event list to view the desired category.

**Manage Offline Data**

- The app supports offline usage, allowing you to add, edit, and delete events even    without an internet connection.

- Offline data will sync with the server once the device is back online.

**Offline Mode and Syncing**

- The app is a PWA that utilizes the following technologies for offline functionality:

    - Service Worker: Caches static assets and pages for offline access.
    - IndexedDB: Stores event data locally for offline use.
    - Sync Logic: Automatically syncs offline data with the server when back online.

**Event Reminder ID Generation**

- Each event is assigned a unique ID with a predefined prefix format, which is set in the backend and applied automatically during event creation.

**Email Reminders with Queues and Cron Jobs**
- Automated Reminders: Emails are scheduled based on the event’s reminder_time.
- Queues: The app uses queues to handle email notifications, enhancing performance by processing emails in the background.
- Cron Job: A cron job runs every minute to trigger Laravel’s schedule:run command for processing email notifications.
- Configuration: Ensure that your mail settings in the .env file are configured correctly for notifications to work.

**Importing Data from CSV**

To import event data :

- Navigate to the Import CSV section.
- Upload a CSV file containing your event data.
- Ensure the CSV follows the required format:
- title, description, reminder_time, start_time, end_time, recipients
- The app validates and imports events, creating or updating entries as necessary.



