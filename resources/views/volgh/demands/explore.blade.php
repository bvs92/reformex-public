@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Exploreaza Cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exploreaza Cereri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				
            <form action="{{ route('demands.explore.results') }}" method="GET">
            <!-- ROW-4 -->
            <div class="row my-8">
                
                <div class="col-lg-4">
                    <div class="form-group">
                        <input type="search" id="address-input" class="form-control @error('city') has-error @enderror" placeholder="Care este orasul proiectului?" name="city" value="{{ old('city') }}">
                        @error('city')
                            <p class="small text-danger">{{ $message }}</p>
                        @enderror
                        <input type="hidden" id="lat" name="lat" />
                        <input type="hidden" id="lng" name="lng" />
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <input type="numeric" 
                        class="form-control @error('range') is-invalid @enderror" id="range" 
                        name="range" placeholder="Distanta in kilometri" value="{{ old('range') ?? 10 }}">
                        @error('range')
                        <p class="small text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        @if($categories && $categories->count() > 0)
                            <select name="categories[]" class="form-control @error('categories') has-error @enderror" id="categories">
                            @foreach($categories as $category)
                                    <option 
                                    value="{{ $category->id }}"
                                    >{{ $category->name }}</option>
                            @endforeach
                            </select>
                        @endif
            
                        @error('categories')
                                <p class="small text-danger">{{ $message }}</p>
                        @enderror
                            
                    </div>
                </div>

                <div class="col-lg-2">
                    <button type="submit" id="search_projects" class="btn btn-md btn-primary "><i class="fa fa-search"></i> Exploreaza cereri</button>
                </div>
            </div>
            </form>
            <!-- ROW-4 END -->

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h2 class="card-title ">Lista cereri disponibile</h2>
                            <div class="card-options">
                                {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    @if($demands && $demands->count() > 0)
                                        @foreach($demands as $demand)   
                                            @if($demand->getNumberBought() < $demand->maximumOffers())
                                            <div class="card mt-2" style="border:none;border-bottom:1px solid #f1f1f1;">
                                                <div class="card-header ">
                                                    <h3 class="card-title ">
                                                        @if($demand->isBought(auth()->user()->professional->id))
                                                            <i class="fa fa-bullseye text-success" aria-hidden="true"></i> {{ $demand->subject }}
                                                        @else
                                                            <i class="fa fa-bullseye text-danger" aria-hidden="true"></i> {{ $demand->subject }}
                                                        @endif
                                                    </h3>
                                                    <div class="card-options">
                                                        {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                                                        <a href="{{ route('demands.show', $demand) }}" class="btn btn-sm btn-info float-right"><i class="fa fa-eye" aria-hidden="true"></i> Vezi detalii complete</a>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            <p class="small"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatCarbonDate($demand->created_at) }} <span>({{ carbonDateToRo($demand->created_at) }})</span></p>
                                                            <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ ucfirst($demand->city) }}</strong> </p>
                                                            
                                                            @if($demand->categories && $demand->categories->count() > 0)
                                                            <p><i class="fa fa-tags" aria-hidden="true"></i>
                                                                @foreach($demand->categories as $category)
                                                                    <span>{{ $category->name }}</span>
                                                                    @if(!$loop->last)
                                                                    <span>| </span> 
                                                                    @endif
                                                                @endforeach
                                                            </p>
                                                            @endif

                                                        </div> <!-- col-md-8 -->
                                                        <div class="col-lg-3">
                                                            <div class="d-flex justify-content-end">
                                                                <p>Status: 
                                                                    @if($demand->isBought(auth()->user()->professional->id))
                                                                        <span class="badge badge-success" style="font-weight:100;"><i class="side-menu__icon ti-unlock"></i> Deblocata</span>
                                                                    @else
                                                                        <span class="badge badge-danger" style="font-weight:100;"><i class="side-menu__icon ti-lock"></i> Blocata</span>
                                                                    @endif

                                                                    @if($demand->belongsToMe())
                                                                        <span>Lansata de mine.</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <p>Stare: @if($demand->isStateActive())
                                                                    <span class="badge badge-success" style="font-weight:100;">Activa</span>
                                                                    @else
                                                                    <span class="badge badge-danger" style="font-weight:100;">Inactiva</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-end">
                                                                <a href="{{ route('demands.show', $demand) }}" class="btn btn-sm btn-default"><i class="fa fa-thumbs-down"></i> Nu ma intereseaza</a>
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                    {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                                                </div>
                                            </div>
                                            @endif {{-- end check number of offers --}}
                                        @endforeach
                            
                                    @else
                                        <p class="text-center m-5">Nu exista cereri inregistrate.</p>
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
	
	

		