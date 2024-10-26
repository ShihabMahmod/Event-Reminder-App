@extends('layout')
@section('content')
    <div class="container mt-5">
        <h1 class="mt-5 mb-5">Register</h1>
        <form action="{{route('store.register')}}" method="POST">
            @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Name</label>
            <input type="text" class="form-control"  name="name" >
        </div>

        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control"  name="email" >
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>

        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" >
        </div>
        
        <div class="mb-3">
            <a href="{{route('login')}}">I have an account</a>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
@endsection