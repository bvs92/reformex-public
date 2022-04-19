@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 style="text-align:center;">This is the contact page.</h1>
            
            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('emails.send') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control @error('email') has-error @enderror" id="email" placeholder="Email " name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Send email</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>    

@endsection