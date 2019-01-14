@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('edit_post', $post->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <input type="text" name="name" placeholder="Name" value="{{ $post->name }}" class="form-control">
            </div>

            <div class="form-group">
                <textarea name="text" rows="10" placeholder="Text" class="form-control">{{ $post->text }}</textarea>
            </div>

            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="img">
                    <label class="custom-file-label" for="customFile">Choose photo</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>

        </form>
    </div>
@endsection