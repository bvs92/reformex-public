@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">


<!-- map -->
{{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   
    <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
   
   <style>
   #mapid { height: 180px; }

   </style> --}}
<!-- end map -->

<style>
    .cbp_tmtimeline>li .cbp_tmlabel h2 {font-weight:100!important;}

.img-thumbnail {
   height: 60px;
}

.cbp_tmtime > span:nth-child(1) {
    font-size:12px!important;
    font-weight:100!important;
}

.cbp_tmtime > span:nth-child(2) {
    font-size:12px!important;
    font-weight:400!important;
}

</style>

@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            @if($demand)
            <h1 class="page-title">Conversatie cu {{ $demand->name }} | #{{ $timeline->getDisponibleId() }}</h1>
            @else
            <h1 class="page-title">Conversatie #{{ $timeline->getDisponibleId() }}</h1>
            @endif
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Conversatie #{{ $timeline->getDisponibleId() }}</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                @if(!$timeline->demand)
                <div class="col-md-12 mb-4">
                    <div class="d-flex justify-content-end">
                        <a onclick="event.preventDefault();document.getElementById('deleteByPro').submit();" class="btn btn-danger btn-sm text-white"><i class="ti-trash"></i> Elimina conversatie</a>           
                        <form 
                            action="{{ route('timeline.delete.pro', $timeline->id) }}" 
                            id="deleteByPro" 
                            method="POST" 
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                @endif

                @if($timeline->demand && $timeline->demand->isCompleted())
                <div class="col-md-12 d-flex justify-content-center">
                    <div class="alert alert-danger" role="alert" style="text-align: center;">
                        <i class="side-menu__icon ti-lock"></i>Cererea este inactiva.
                    </div>
                </div>
                @endif
            </div>
            <div class="row">

                <!-- vue tiomeline -->
                <timeline-pro-component :the_accessTokenMap="{{ json_encode(config('services.mapbox.api_key')) }}" :the_demand="{{ json_encode($demand) }}" :the_current_user="{{ json_encode(auth()->user()) }}" :incoming_timeline="{{ $timeline }}" :demand_cost="{{ $demand_cost->amount }}" :demand_unlocked="{{ json_encode($unlocked_demand) }}"></timeline-pro-component>

            </div>
            <!-- ROW-1 CLOSED -->
        </div>
    </div>
    <!-- CONTAINER CLOSED -->
</div>
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/accordion/accordion.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>


{{-- @if($demand)
<script>
    

    $lng = {{ $demand->lng }};
    $lat = {{ $demand->lat }};

    var mymap = L.map('mapid').setView([$lat, $lng], 10);

    
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: "{{ config('services.mapbox.api_key') }}"
    }).addTo(mymap);
    
    var marker = L.marker([$lat, $lng]).addTo(mymap);
    
    var circle = L.circle([$lat, $lng], {
        color: 'green',
        fillColor: 'green',
        fillOpacity: 0.1,
        radius: 8000
    }).addTo(mymap);
    
</script>
@endif --}}

@endsection