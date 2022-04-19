@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Edit a post</h2>

            <form action="{{ route('posts.update', $post) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="titlePost">Title</label>
                <input type="text" class="form-control" id="titlePost" placeholder="Post title" name="title" value="{{ $post->title ?? old('title') }}">
                </div>


                <div class="form-group">
                    <label for="postContent">Post content</label>
                <textarea class="form-control" id="postContent" rows="3" name="body">{{ $post->body ?? old('body') }}</textarea>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Update post</button>
                    </div>
                </div>

            </form>



        </div>
    </div>
</div>   

@endsection