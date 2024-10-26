


@extends('layout')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mt-5">Upcoming Events</h1>
            </div>
            <div class="">
                <a href="{{ route('event.create') }}" class="btn btn-outline-success">Add New</a>
                <a href="{{ route('event.import.index') }}" class="btn btn-outline-info">Import</a>
            </div>
        </div>

        <?php
            use Carbon\Carbon;
        ?>

        <table class="table mt-5">
            <thead>
                <tr>
                <th scope="col">S.N</th>
                <th scope="col">Title</th>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">Reminder Time</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($upcomingEvents as $index => $event)
                <tr>
                    <th scope="row">{{$index + 1}}</th>
                    <td>{{ $event->title }}</td>
                    <td>{{ Carbon::parse($event->start_time)->format('F j, Y g:i A') }}</td>
                    <td>{{ Carbon::parse($event->end_time)->format('F j, Y g:i A') }}</td>
                    <td>{{ Carbon::parse($event->reminder_time)->format('F j, Y g:i A') }}</td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-info" href="{{ route('event.edit', $event->id) }}">Edit</a>
                        <form action="{{ route('event.destroy', $event->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h1 class="mt-5">Completed Events</h1>
        <table class="table mt-5">
            <thead>
                <tr>
                <th scope="col">S.N</th>
                <th scope="col">Title</th>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">Reminder Time</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                
            @foreach($completedEvents as $index => $event)
                <tr>
                    <th scope="row">{{$index + 1}}</th>
                    <td>{{ $event->title }}</td>
                    <td>{{ Carbon::parse($event->start_time)->format('F j, Y g:i A') }}</td>
                    <td>{{ Carbon::parse($event->end_time)->format('F j, Y g:i A') }}</td>
                    <td>{{ Carbon::parse($event->reminder_time)->format('F j, Y g:i A') }}</td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-info" href="{{ route('event.edit', $event->id) }}">Edit</a>
                        <form action="{{ route('event.destroy', $event->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection