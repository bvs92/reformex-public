@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">

<!-- map -->
{{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/> --}}
   
    <!-- Make sure you put this AFTER Leaflet's CSS -->
 {{-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script> --}}
   
   <style>
   #mapid { height: 220px; }

   </style>
<!-- end map -->

@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('demands.index') }}">Cereri</a></li>
                @if($demand->hasUUID())
                <li class="breadcrumb-item active" aria-current="page">Cerere #{{ $demand->uuid }}</li>
                @else
                <li class="breadcrumb-item active" aria-current="page">Cerere #{{ $demand->id }}</li>
                @endif
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-5 -->
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header ">ADMIN
                        @if($demand->hasUUID())
                        <h2 class="card-title ">Numar ordine cerere: #{{ $demand->uuid }}</h2>
                        @else
                        <h2 class="card-title ">Numar ordine cerere: #{{ $demand->id }}</h2>
                        @endif


                        @role('admin')
                        <div class="card-options">
                            {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                            <div class="dropdown float-right">
                                <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti-more"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                   
                                    @if($demand->hasUUID())
                                    <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina UUID</a>
                                   
                                    <form 
                                        action="{{ route('demands.destroy.uuid', $demand->uuid) }}" 
                                        id="deleteDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                    @else
                                    <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                   
                                    <form 
                                        action="{{ route('demands.destroy', $demand->id) }}" 
                                        id="deleteDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                    @endif

                                </div>


                            </div>

                        </div><!-- end card options -->
                        @endrole




                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <div class="">
                                <div class="row justify-content-center p-4">
                                    <div class="col-md-8 p-4" style="background: white;">

                                        <h4><span class="text-muted">Titlu:</span> {{ $demand->subject }}</h4>
                                        <hr>
                                        

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p>Numar ordine: #{{ $demand->getDemandId() }}</p>
                                                <p>Publicat: <i class="side-menu__icon ti-time"></i> {{ carbonDateToRo($demand->created_at) }}</p>
                                            </div>
                                            <div class="col-lg-6">
                                                <p>Locatie: <i class="side-menu__icon ti-location-pin"></i> {{ ucfirst($demand->city) }}</p>
                                                @if($demand->categories && $demand->categories->count() > 0)
                                                <p>Categorii: <i class="fa fa-tags" aria-hidden="true"></i>
                                                    @foreach($demand->categories as $category)
                                                        <span>{{ $category->name }}</span>
                                                        @if(!$loop->last)
                                                        <span>| </span> 
                                                        @endif
                                                    @endforeach
                                                </p>
                                                @endif
                                                {{-- <div id='mapid'></div> --}}
                                                {{-- <p><i class="side-menu__icon ti-location-pin"></i> {{ ucfirst($demand->city) }}</p> --}}
                                            </div>
                                        </div>

                                        {{-- <div class="row">
                                            <div class="col-lg-6">
                                                @if($demand->categories && $demand->categories->count() > 0)
                                                <p>Categorii: <i class="fa fa-tags" aria-hidden="true"></i>
                                                    @foreach($demand->categories as $category)
                                                        <span>{{ $category->name }}</span>
                                                        @if(!$loop->last)
                                                        <span>| </span> 
                                                        @endif
                                                    @endforeach
                                                </p>
                                                @endif
                                            </div>
                                            <div class="col-lg-6">
                                                <p class="text-danger"><i class="side-menu__icon ti-bolt"></i> Urgent</p>
                                            </div>
                                        </div> --}}

                                        {{-- <hr> --}}

                                        <div id='mapid'></div>

                                        <hr>

                                        {{-- <div>
                                            <h4 class="text-muted">Fisiere atasate</h4>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-6">
                                                    <a href="#" class="thumbnail ">
														<img src="{{URL::asset('assets/images/media/1.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-6">
                                                    <a href="#" class="thumbnail ">
														<img src="{{URL::asset('assets/images/media/1.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-6">
                                                    <a href="#" class="thumbnail ">
														<img src="{{URL::asset('assets/images/media/1.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
                                                </div>
                                            </div>
                                        </div> --}}

                                        @if($demand->files && $demand->files->count() > 0)
                                        <div>
                                            <h4 class="text-muted">Fisiere atasate</h4>
                                            <div class="row">
                                                @foreach($demand->files as $theFile)
                                                {{-- <div class="col-lg-4 col-md-6 col-6">
                                                    <a href="#" class="thumbnail ">
														<img src="{{url('storage/demands/' . $file->name)}}" class="thumbimg">
													</a>
                                                </div> --}}

                                                <div class="col-lg-3 col-md-6 col-6 my-2">
                                                    @if($theFile->mime_type == 'image/jpeg' || $theFile->mime_type == 'image/png' || $theFile->mime_type == 'image/webp')
                                                        <a class="look-file" href="{{asset('storage/demands/' . $theFile->name)}}" data-lightbox="photos">
                                                            <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/demands/' . $theFile->name)}}" alt="{{ $theFile->name }}">
                                                        </a>
                                                        <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/pdf')
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'text/csv')
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/msword')
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>

                                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/vnd.ms-excel')
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == "text/plain")
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @else
                                                            <a class="look-file" href="{{URL::asset('storage/demands/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> <span class="file-name">{{ $theFile->name }}</span>
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @endif
                                                </div>

                                                @endforeach
                                            </div>
                                        </div>
                                        <hr>
                                        @endif

                                        <hr>
                                
                                        <div>
                                            <h4 class="text-muted">Descriere cerere</h4>
                                            {{ $demand->message }}
                                        </div>
                                        
{{--                                         
                                        <hr>
                                        <p>Localizare: 20 KM de locatia dvs (Bucuresti)</p>
                                        <img src="{{ asset('images/staticmap.png') }}" alt=""> --}}
                                    
                                        <br>
                                    </div><!-- end col-lg-8 -->
                                
                                    <div class="col-md-4 p-4">
                                        <h4>Detalii de contact</h4>
                                        <hr>
                                        <div class="card shadow-sm">
                                            <div class="card-body" style="padding:1rem 1.5rem;">
                                                <p class="text-muted" style="font-size:18px;"><i class="fa fa-user"></i> {{ $demand->name }}</p>
                                                <p class="text-muted" style="font-size:18px;"><i class="fa fa-at"></i> {{ $demand->email }}</p>
                                                <p class="text-muted" style="font-size:18px;"><i class="fa fa-phone"></i> {{ $demand->phone }}</p>
                                            </div>
                                        </div>
                                
                                       
                                            {{-- <button type="button" class="btn btn-primary btn-block my-2"><i class="fa fa-send"></i> Trimite mesaj</button> --}}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <p class="text-center">Pret deblocare: {{ $demand->getCalculatedPriceNormal() }} RON</p>
                                                </div>
                                            </div>
                                        

                                        @role('admin')
                                        <br><br>
                                        <h4>Actiuni</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p class="mt-4">Stare cerere: @if($demand->getState() == 1) <span class="badge badge-success">Activa</span> @else <span class="badge badge-default">Inactiva</span> @endif</p>
                                            </div>
                                            <div class="col-lg-6">
                                                    @if($demand->hasUUID())
                                                    <a onclick="event.preventDefault();document.getElementById('changeState').submit();" class="btn btn-sm btn-blue mt-4 text-white d-flex justify-content-center"><i class="ti-reload"></i> Schimba stare</a>
                                                   
                                                    <form 
                                                        action="{{ route('demands.change.state.uuid', $demand->uuid) }}" 
                                                        id="changeState" 
                                                        method="POST" 
                                                        style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                    @else
                                                    <a onclick="event.preventDefault();document.getElementById('changeState').submit();" class="btn btn-sm btn-blue mt-4 text-white d-flex justify-content-center"><i class="ti-reload"></i> Schimba stare</a>
                                                   
                                                    <form 
                                                        action="{{ route('demands.change.state', $demand->id) }}" 
                                                        id="changeState" 
                                                        method="POST" 
                                                        style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                    @endif
                                            </div>

                                        </div>

                                        @if($demand->hasUUID())
                                            @include('volgh.demands._partials.status-part-uuid')
                                        @else
                                            @include('volgh.demands._partials.status-part-id')
                                        @endif

                                        <br><br>

                                        @if($demand->isBought(auth()->user()->professional->id))
                                            <hr>
                                            @if($demand->isUnverified())
                                                @if($demand->isReportedBy(auth()->user()))
                                                <div class="col-lg-12">
                                                    <p class="text-center"><i class="side-menu__icon ti-alert"></i> Ati raportat aceasta cerere. <a href="{{ route('demands_reports.show', $demand->getReportFor(auth()->user())->id) }}" class="btn btn-sm btn-default"><i class="ti-link"></i> Vezi</a></p>
                                                </div>
                                                @else
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <a href="{{ route('demands_reports.create', $demand->id) }}" class="btn btn-sm btn-default my-4 d-flex justify-content-center"><i class="ti-alert mx-1"></i> Raporteaza cererea</a>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                            
                                            @if($demand->isVerified())
                                                <p class="text-center"><span class="badge badge-success mr-1 mb-1 mt-1"><i class="ti-check-box"></i></span> Cererea a fost verificata si validata.</p>
                                                @if($demand->isReportedBy(auth()->user()))
                                                <div class="col-lg-12">
                                                    <p class="text-center"><i class="side-menu__icon ti-alert"></i> Ati raportat aceasta cerere. <a href="{{ route('demands_reports.show', $demand->getReportFor(auth()->user())->id) }}" class="btn btn-sm btn-default"><i class="ti-link"></i> Vezi</a></p>
                                                </div>
                                                @endif
                                            @endif

                                            @if($demand->isFalse())
                                                <p class="text-center"><span class="badge badge-danger mr-1 mb-1 mt-1"><i class="ti-alert"></i></span> Cererea a fost verificata si invalidata.</p>
                                                @if($demand->isReportedBy(auth()->user()))
                                                <div class="col-lg-12">
                                                    <p class="text-center"><i class="side-menu__icon ti-alert"></i> Ati raportat aceasta cerere. <a href="{{ route('demands_reports.show', $demand->getReportFor(auth()->user())->id) }}" class="btn btn-sm btn-default"><i class="ti-link"></i> Vezi</a></p>
                                                </div>
                                                @endif
                                            @endif

                                        @endif

                                        {{-- @if(!$demand->isReportedBy(auth()->user()))
                                            <div class="row">
                                                <div class="col-lg-12 my-4">
                                                    <a href="{{ route('demands_reports.create', $demand->id) }}" class="btn btn-sm btn-default my-4 d-flex justify-content-center"><i class="ti-alert mx-1"></i> Raporteaza cererea</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-12 my-4">
                                                <p class="text-center"><i class="side-menu__icon ti-alert"></i> Ati raportat aceasta cerere. <a href="{{ route('demands_reports.show', $demand->getReportFor(auth()->user())->id) }}" class="btn btn-sm btn-default"><i class="ti-link"></i> Vezi</a></p>
                                            </div>
                                        @endif --}}

                                        <p class="text-center mt-4">Numar raportari: {{ $demand->reports->count() }}.</p>
                                        @if($demand->reports && $demand->reports->count() > 0)
                                            @foreach($demand->reports as $report)
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        @if($report->user->hasProfilePhoto())
                                                            <img src="{{ asset($report->user->getFullProfilePhoto()) }}" alt="{{ $report->user->getName() }}" class="avatar avatar-md rounded-circle">
                                                        @else
                                                            <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ $report->user->getName() }}" class="avatar avatar-md rounded-circle">
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <p class="mt-3">
                                                            @if($report->user->isPro())
                                                                {{ $report->user->professional->getName() }} {{ $report->user->id == auth()->user()->id ? "(Eu)" : '' }}
                                                            @else
                                                                {{ $report->user->getName() }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <a class="btn btn-sm btn-info mt-3" href="{{ route('demands_reports.show', $report->id) }}">Vezi</a> 
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif {{-- end reports --}}

                                        

                                        @endrole

                                        

                                        
                                        
                                
                                    </div><!-- end col-lg-4 -->
                            </div><!-- end row -->

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
    
    // var marker = L.marker([$lat, $lng]).addTo(mymap);
    
    var circle = L.circle([$lat, $lng], {
        color: 'green',
        fillColor: 'green',
        fillOpacity: 0.1,
        radius: 8000
    }).addTo(mymap);
    
</script>



@endsection
			
	
	

		