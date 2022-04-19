@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Editeaza tip abonament</h2>

<form action="{{ route('subscriptions.update', $subscription) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Nume</label>
        <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele abonamentului" name="name" value="{{ old('name') ?? $subscription->name}}">
        @error('name')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>


    <div class="form-group row">
        <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Editeaza tip abonament</button>
        </div>
    </div>


</form>

<br>
<br>
<br>
<h3>De adaugat Roluri si alte functionalitati.</h3>

@endsection