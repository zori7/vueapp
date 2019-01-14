@extends('layouts.app')

@section('content')
    <div class="container">

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('create_post') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ old('name')  }}">
            </div>

            <div class="form-group">
                <textarea name="text" rows="10" placeholder="Text" class="form-control">{{ old('text') }}</textarea>
            </div>

            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="img">
                    <label class="custom-file-label" for="customFile">Choose photo</label>
                </div>
            </div>

                <button type="submit" class="btn btn-success">Create</button>

        </form>

    </div>
@endsection