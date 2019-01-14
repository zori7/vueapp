@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="post row">

            @if($post->img != "storage/no-image.png")
            <div class="col-md-9 mx-auto my-2 col-sm-12">
                <img src="/{{ $post->img }}" class="img-fluid mx-auto" alt="">
            </div>
            @endif


            <div class="col-md-12 text-center">
                <h1 class="text-primary">{{ $post->name }} by {{ $username }}</h1>

                <p>
                    {{ $post->text }}
                </p>
            </div>

        </div>

    </div>
@endsection