

@extends('layout')
@section('content')
    <div class="container mt-5">
        <h1 class="mb-5">Edit Event</h1>
        <form action="{{ route('event.update',$event->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="exampleFormControlInput1" value="{{$event->title}}">
            </div>

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" name="description">{{$event->description}}</textarea>
            </div>
           

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Start Time</label>
                <input type="datetime-local" class="form-control" name="start_time" id="exampleFormControlInput1" value="{{$event->start_time}}">
            </div>

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">End Time</label>
                <input type="datetime-local" class="form-control" name="end_time" id="exampleFormControlInput1" value="{{$event->end_time}}">
            </div>

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Reminder Time</label>
                <input type="datetime-local" class="form-control" name="reminder_time" id="exampleFormControlInput1" value="{{$event->reminder_time}}">
            </div>
            
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
@endsection