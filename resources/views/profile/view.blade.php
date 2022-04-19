@extends('layouts.app')


@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection



@section('content')

<div class="row">
        <div class="col-lg-12 my-4">
        <h3>Modifica informatii profil</h3>
        <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')
        
                <div class="form-row">
                        <div class="col">
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Popescu" value="{{ auth()->user()->first_name ?? old('first_name') }}">
                                @error('first_name')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        <div class="col">
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Andrei" value="{{ auth()->user()->last_name ?? old('last_name') }}">
                                @error('last_name')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                </div>
                <br>
                <div class="form-row">
                        <div class="col">
                                <input type="text" disabled="disabled" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nume@email.com" value="{{ auth()->user()->email ?? old('email') }}">
                                @error('email')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        <div class="col">
                                <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                        </div>
                </div>
        </form>
        </div>


        <!-- Modify profile photo -->
        <div class="col-lg-12">
                <h3>Modifica poza de profil</h3>
        </div>
        <div class="col-lg-6">
                @if(auth()->user()->hasProfilePhoto())
                <div class="block">
                        <img src="{{ asset(auth()->user()->getFullProfilePhoto()) }}" class="rounded" alt="{{ auth()->user()->getName() }}" width="300px">
                </div>
                <div class="block m-auto">
                        <a class="btn btn-danger btn-sm text-white" onclick="event.preventDefault();document.getElementById('deleteProfilePhoto').submit();">Elimina avatar</a>
                        <form action="{{ route('profile.photo.delete', auth()->user()->profile->id) }}" method="POST" id="deleteProfilePhoto" style="display: none;">
                                @csrf
                                @method('DELETE')
                        </form>
                </div>
                @else
                        <p>Nu aveti o poza de  profil. Va rugam sa adaugati una.</p>
                @endif
        </div>
        <div class="col-lg-6">
                <form method="POST" action="{{ route('user.profile.update.photo') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                
                        <div class="form-row">
                                
                                <div class="form-group">
                                        <label for="profile_photo">Alege fotografia de profil</label>
                                        <input type="file" name="profile_photo" class="form-control-file @error('profile_photo') is-invalid @enderror" id="profile_photo" >
                                        @error('profile_photo')
                                        <p class="small text-danger">{{ $message }}</p>
                                        @enderror
                                </div>
                        </div>
                        <div class="form-row">
                                <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                        </div>
                </form>
        </div>
        
        {{-- <div class="col-lg-12 my-4">
                <form method="POST" action="{{ route('user.profile.update.photo') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                
                        <div class="form-row">
                                <div class="col">
                                        <div class="form-group">
                                                <label for="profile_photo">Alege fotografia de profil</label>
                                                <input type="file" name="profile_photo" class="form-control-file @error('profile_photo') is-invalid @enderror" id="profile_photo" >
                                                @error('profile_photo')
                                                <p class="small text-danger">{{ $message }}</p>
                                                @enderror
                                        </div>
                                </div>

                                <div class="col">
                                        <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                                </div>


                        </div>
                </form>
        </div><!-- avatar --> --}}


        @if(auth()->user()->isPro())
        <div class="col-lg-12 mt-4">
        {{-- <p>Denumire firma: {{ auth()->user()->professional->getName() }}</p> --}}
        <h3>Modifica informatii firma</h3>
        <br>
        <p>Locatie: <strong>{{ auth()->user()->professional->getLocation() }}</strong></p>


                
        @if(auth()->user()->professional->categories)   
      
                <h4>Categorii asociate</h4>
                <div class="">
                        <form method="POST" action="{{ route('professionals.update.categories') }}">
                                @csrf
                                @method('PUT')
                                 <div class="form-group">
                                        <label for="categories">Categorii</label>
                                        @if($categories && $categories->count() > 0)
                                                <select name="categories[]" class="form-control @error('categories') has-error @enderror" id="categories" multiple>
                                                @foreach($categories as $category)
                                                        <option 
                                                        value="{{ $category->id }}"
                                                        @if($my_categories->contains($category))
                                                                selected="selected"
                                                        @endif
                                                        >{{ $category->name }}</option>
                                                @endforeach
                                                </select>
                                        @endif
                        
                                        @error('categories')
                                                <p class="small text-danger">{{ $message }}</p>
                                        @enderror
                                        
                                </div>
                        
                                <div class="form-group">
                                        <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                                </div>
                                </form>
                </div>
                <div class="">
                        <!-- eliminate categories -->
                        <form action="{{ route('professionals.detach.categories') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger mb-2">Sterge categoriile atasate</button>
                        </form>
                </div>
  
        
       

        <ul>
                {{-- @foreach(auth()->user()->getAssocCategories() as $category)
                     <li>{{ $category->name }}</li>
                @endforeach --}}
        </ul>
        @endif
        <br>
        <br><br>

        
        <br>
        <br>
        <br>
        <!-- end eliminate categories -->


        <form method="POST" action="{{ route('professionals.updatePro') }}">
                @csrf
                @method('PUT')
                <div class="form-row">
                        <div class="col">
                                <input type="text" class="form-control @error('pro_name') is-invalid @enderror" id="pro_name" name="pro_name" placeholder="Nume firma" value="{{ auth()->user()->professional->getName() ?? old('pro_name') }}">
                                @error('pro_name')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        <div class="col">
                                <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                        </div>
                </div>

                <div class="form-group">
                        <label for="city">Oras</label>
                        <input type="search" id="address-input" 
                                class="form-control @error('city') has-error @enderror" 
                                placeholder="Care este locatia (orasul) firmei?" 
                                name="city" 
                                value="{{ old('city') }}">
                        @error('city')
                                <p class="small text-danger">{{ $message }}</p>
                        @enderror
                </div>

                <input type="hidden" id="the-city" name="the-city" />
                <input type="hidden" id="administrative" name="administrative" />
                <input type="hidden" id="postal_code" name="postal_code" />
                <input type="hidden" id="lat" name="lat" />
                <input type="hidden" id="lng" name="lng" />


        </form>
        </div>
        @endif

        <div class="col-lg-12 mt-4">
        <h3>Modifica parola</h3>
        <form method="POST" action="{{ route('user.password.change') }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                        <div class="col">
                                <label for="password">Parola curenta</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        <div class="col">
                                <label for="new_password">Noua parola</label>
                                <input type="text" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                @error('new_password')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>

                        <div class="col">
                                <label for="new_password_confirmation">Confirma noua parola</label>
                                <input type="text" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation">
                                @error('new_password_confirmation')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                </div>
                <br>
                <div class="form-row">
                        <div class="col">
                                <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                        </div>
                </div>
        </form>
        </div>

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
        aroundLatLngViaIP: false 
    };
    const placesInstance = places(fixedOptions).configure(reconfigurableOptions);

    placesInstance.on('change', function(e){
        console.log(e.suggestion);
        document.getElementById('the-city').value = e.suggestion.name;
        document.getElementById('administrative').value = e.suggestion.administrative;
        document.getElementById('postal_code').value = e.suggestion.postcode;
        document.getElementById('lat').value = e.suggestion.latlng.lat;
        document.getElementById('lng').value = e.suggestion.latlng.lng;

        
    })

    
  </script>

@endsection