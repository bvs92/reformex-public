@extends('volgh.layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">

<style>
.img-thumbnail {
   height: 160px;
}

</style>

@endsection

@section('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('title-page')
<title>Editare proiect #{{ $current_project->uuid }}</title>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Proiecte</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proiecte</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Editare proiect #{{ $current_project->id }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <form action="{{ route('work-projects.update', $current_project->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="subject">Titlu</label>
                                            <input type="text" class="form-control @error('title') has-error @enderror" id="title" placeholder="Denumire proiect" name="title" value="{{ old('title') ?? $current_project->title }}">
                                            @error('title')
                                                <p class="small text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                    
                                    
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="city">Oras (optional)</label>
                                                    <input type="search" id="address-input" class="form-control @error('city') has-error @enderror" placeholder="Care este orasul proiectului?" name="city" value="{{ old('city') ?? $current_project->city }}">
                                                    @error('city')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                    <input type="hidden" id="lat" name="lat" value="{{ old('lat') ?? $current_project->lat }}" />
                                                    <input type="hidden" id="lng" name="lng" value="{{ old('lng') ?? $current_project->lng }}" />
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="postal_code">Cod postal (optional)</label>
                                                    <input type="text" id="postal_code" class="form-control @error('postal_code') has-error @enderror" placeholder="Cod postal" name="postal_code" value="{{ old('postal_code') ?? $current_project->postal_code }}">
                                                    @error('postal_code')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    
                                    
                                        {{-- <div class="form-group">
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
                                        </div> --}}
                                    
                                        <div class="form-group">
                                            <label for="description">Descriere proiect</label>
                                            <textarea class="form-control 
                                            @error('description') has-error @enderror" 
                                            name="description" id="description" cols="30" rows="7">{{ old('description') ?? $current_project->description }}</textarea>
                                            @error('description')
                                                <p class="small text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                            <button type="submit" class="btn btn-success">Salveaza proiect</button>
                                            </div>
                                        </div>
                                    
                                    
                                    </form>

                                    <div class="my-4">
                                        <h4>Categorii proiect</h4>
                                        
                                        <form action="{{ route('work-projects.update.categories', $current_project->id) }}" method="POST">
                                            @csrf
                                                <div class="form-group">
                                                    <label for="categories">Categorii proiecte</label>
                                                    @if($categories && $categories->count() > 0)
                                                        <select name="categories[]" class="form-control @error('categories') has-error @enderror" id="categories" multiple>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->id }}" @if($current_project->categories->contains($cat->id)) selected="selected" @endif>{{ $cat->name }}</option>
                                                        @endforeach
                                                        </select>
                                                    @endif
                                            
                                                    @error('categories')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                  </div>
                                            

                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                <button type="submit" class="btn btn-success">Salveaza categorii</button>
                                                </div>
                                            </div>
                                        </form>
                                        
                                    </div>


                                    @if($current_project->photos && $current_project->photos->count() > 0)
                                        <h4>Fotografii proiect</h4>
                                            <div class="row m-4">
                                                @foreach($current_project->photos as $the_photo)
                                                    <div class="col-lg-3">
                                                        <a href="{{asset('storage/work_projects/' . $the_photo->name)}}" data-lightbox="photos">
                                                            <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/work_projects/' . $the_photo->name)}}" alt="{{ $the_photo->name }}" />
                                                        </a>

                                                        <div class="row">
                                                            <div class="col-lg-6 d-flex justify-content-center my-2">
                                                                <a onclick="event.preventDefault();document.getElementById('deletePhoto-{{$the_photo->id}}').submit();" class="btn btn-sm btn-danger">Elimina</a>
                                                                
                                                                <form action="{{ route('work-projects-photos.destroy', $the_photo->id) }}" 
                                                                    method="POST" 
                                                                    id="deletePhoto-{{ $the_photo->id }}" 
                                                                    style="display: none;"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>


                                                            <div class="col-lg-6 d-flex justify-content-center my-2">
                                                                <a href="{{ route('files.download',  ['type' => 'work_projects', 'file_name' => $the_photo->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}


<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
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
        document.getElementById('postal_code').value = e.suggestion.postcode;
        
    })

    
  </script>

@endsection
			
	
	

		