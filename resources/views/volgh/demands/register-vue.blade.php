@extends('volgh.layouts.master')
@section('css')

@endsection

@section('head-scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script> --}}
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Adauga o cerere</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Adauga cerere noua</li>
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
                            <h3 class="card-title ">Adauga o noua cerere</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <register-demand-component></register-demand-component>
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




@section('footer-scripts')
{{-- <script src="{{ asset('js/validation/jquery.validate.min.js') }}"></script> --}}
{{-- <script>


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
        
    });

    
    
  </script> --}}

@endsection
			
	
	

		