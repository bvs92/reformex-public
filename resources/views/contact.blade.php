@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 style="text-align:center;">This is the contact page.</h1>
            <h2>Param: {{ $param }}</h2>
            <h3>Time is {{ time() }} and 2+2 = {{ 2+2 }}</h3>
            <pre>
                @json(['a' => 'b', 'c' => 'd'], JSON_PRETTY_PRINT)
        </div>
    </div>
</div>    

@endsection
