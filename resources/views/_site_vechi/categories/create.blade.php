@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Adauga categorie</h2>

<form action="{{ route('categories.store') }}" method="POST">
    @csrf


    <div class="form-group">
        <label for="name">Denumire</label>
        <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele categoriei" name="name" value="{{ old('name') }}">
        @error('name')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="price">Pret</label>
        <input type="text" 
        class="form-control @error('price') has-error @enderror" 
        id="price" 
        placeholder="Pretul cererilor din categorie" 
        name="price" value="{{ old('price') }}">
        @error('price')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>


    <div class="form-group">
        <label for="description">Descriere categorie</label>
        <textarea class="form-control @error('description') has-error @enderror" name="description" id="description" cols="30" rows="10">
            {{ old('description') }}
        </textarea>
        @error('description')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group row">
        <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Creeaza categorie</button>
        </div>
    </div>


</form>

@endsection