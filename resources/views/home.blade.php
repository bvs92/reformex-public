@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>You are logged in! Count: {{ $count }}</p>
                    
                    {{-- <button class="btn btn-primary">Click me</button> --}}

                    @if(!auth()->user()->isPro())
                        <p>Pentru a putea comunica cu clientii, va rugam sa va activati contul de firma.</p>
                        <form action="{{ route('professionals.activate') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Denumire firma</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Denumire firma">
                            </div>

                            <button class="btn btn-primary">Activeaza contul de firma.</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <script>
    function lunchAlert($message){
        Toast.fire({
        icon: 'success',
        title: 'Signed in successfully'
        });
    }
</script> --}}

@endsection
