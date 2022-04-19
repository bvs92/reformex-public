@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Create a post</h2>

            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="titlePost">Title</label>
                <input type="text" class="form-control @error('title') has-error @enderror" id="titlePost" placeholder="Post title" name="title" value="{{ old('title') }}">
                    @error('title')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="postContent">Post content</label>
                    <textarea class="form-control" id="postContent" rows="3" name="body">
                        {{ old('body') }}
                    </textarea>
                    @error('body')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Create post</button>
                    </div>
                </div>

            </form>



        </div>
    </div>
</div>   

@endsection