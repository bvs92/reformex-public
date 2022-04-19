@extends('layouts.app');


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Listing the posts</h2>

            <ul>
                @foreach($posts as $post)
            <li>Id: {{ $post->id }} - {{$post->title}} | <a href="{{ route('posts.edit', $post) }}">Edit</a> | <form method="POST" action="{{ route('posts.delete', $post) }}">@csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">Delete</button></form></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>   


@endsection

