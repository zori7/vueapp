@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="user row">

            <div class="col-md-12 text-center">
                <h1 class="text-primary">Name: {{ $user->name }}</h1>

                    Email: {{ $user->email }}<br>
                    Joined: {{ $user->created_at }}<br>
                    Admin: {{ $isAdmin }}

            </div>

        </div>

    </div>
@endsection