@extends('layout')
@section('content')
    <div class="container mt-5">
        <h1 class="mt-5 mb-5">Login</h1>
        <form action="{{route('store.login')}}" method="POST">
            @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="exampleInputPassword1">
        </div>
        
        <div class="mb-3">
            <a href="{{route('register')}}">I have no account</a>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
@endsection