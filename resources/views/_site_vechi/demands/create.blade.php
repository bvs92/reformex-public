@extends('layouts.app')


@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection

@section('content')

<h2 style="text-align:center;">Adauga cerere</h2>

{{-- <input type="search" id="address-input" placeholder="Where are we going?" /> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script> --}}



<form action="{{ route('demands.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="subject">Subiect cerere</label>
        <input type="text" class="form-control @error('subject') has-error @enderror" id="subject" placeholder="Subiect cerere" name="subject" value="{{ old('subject') }}">
        @error('subject')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="name">Nume</label>
        <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele dvs" name="name" value="{{ old('name') }}">
        @error('name')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" class="form-control @error('email') has-error @enderror" id="email" placeholder="Numar telefon" name="email" value="{{ old('email') }}">
        @error('email')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="phone">Telefon</label>
        <input type="text" class="form-control @error('phone') has-error @enderror" id="phone" placeholder="Numar telefon" name="phone" value="{{ old('phone') }}">
        @error('phone')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="city">Oras</label>
        <input type="search" id="address-input" class="form-control @error('city') has-error @enderror" placeholder="Care este orasul proiectului?" name="city" value="{{ old('city') }}">
        @error('city')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
        <input type="hidden" id="lat" name="lat" />
        <input type="hidden" id="lng" name="lng" />
    </div>


    <div class="form-group">
        <label for="categories">Categorii</label>
        @if($categories && $categories->count() > 0)
            <select name="categories[]" class="form-control @error('categories') has-error @enderror" id="categories" multiple>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
            </select>
        @endif

        @error('categories')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
      </div>

    <div class="form-group">
        <label for="message">Descrieti cat mai clar cererea</label>
        <textarea class="form-control 
        @error('message') has-error @enderror" 
        name="message" id="message" cols="30" rows="10">{{ old('message') }}</textarea>
        @error('message')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group row">
        <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Trimite cerere</button>
        </div>
    </div>


</form>

@endsection


@section('footer-scripts')

<script>

    // De preluat mai multe informatii din algolia? Cod postal, lat, long? Tabel separat doar pentru aceste informatii?
    // var placesAutocomplete = places({
    //   appId: '{{ config('services.algolia.appId') }}',
    //   apiKey: '{{ config('services.algolia.apiKey') }}',
    //   container: document.querySelector('#address-input')
      
    // });

    const fixedOptions = {
        appId: '{{ config('services.algolia.appId') }}',
        apiKey: '{{ config('services.algolia.apiKey') }}',
        container: document.querySelector('#address-input')
    };

    const reconfigurableOptions = {
        language: 'ro', 
        countries: ['ro'], 
        type: 'city', 
        aroundLatLngViaIP: false ,
        getRankingInfo: true
    };
    const placesInstance = places(fixedOptions).configure(reconfigurableOptions);

    placesInstance.on('change', function(e){
        console.log(e.suggestion.hit._rankingInfo);
        console.log(e.suggestion);
        document.getElementById('lat').value = e.suggestion.latlng.lat;
        document.getElementById('lng').value = e.suggestion.latlng.lng;
        
    })

    
  </script>

@endsection