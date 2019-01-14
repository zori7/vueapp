@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('edit_user', $user->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
            </div>
            <div class="form-group">
                <label for="password">New password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection