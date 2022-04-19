@extends('layouts.app');

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 style="text-align:center;">Notifications</h1>
            
            @foreach($notifications as $notification)
            {{-- {{ dd($notification) }} --}}
                <li>{{ $notification->id }}: {{ $notification->data['text'] }}</li>
            @endforeach
        </div>
    </div>
</div>  

@endsection