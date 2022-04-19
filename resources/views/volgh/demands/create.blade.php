@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categorii</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				

            <!-- ROW-4 -->
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Numar cereri</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">##</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>+24%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart1" class="chart-dropshadow-info"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Cotatii trimise</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">##</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>90.5%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart2" class="chart-dropshadow-secondary"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Cereri Inregistrate azi</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">##</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>20.8%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart3" class="chart-dropshadow-success"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-4 END -->

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Adauga o noua cerere</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
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
                                            <button type="submit" class="btn btn-success">Inregistreaza cerere</button>
                                            </div>
                                        </div>
                                    
                                    
                                    </form>
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
			
	
	

		