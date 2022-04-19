@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cereri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="">
                        <div class="card-header">
                            <h2 class="card-title">Lista cereri disponibile ({{ $demands->count() }})</h2>
                            <div class="card-options">
                                <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    @if($demands)
                                        @foreach($demands as $demand)   
                                            @if($demand->getState() == 1 && $demand->getStatus() !== 2)
                                            <div class="card mt-2" style="border:none;border-bottom:1px solid #f1f1f1;">
                                                <div class="card-header ">
                                                    <h3 class="card-title ">
                                                        @if($demand->belongsToMe())
                                                            <i class="fa fa-bullseye text-success" aria-hidden="true"></i> {{ $demand->subject }}  (Proprietar)
                                                        @else
                                                            @if($demand->hasProfessional(auth()->user()->professional))
                                                                <i class="fa fa-bullseye text-success" aria-hidden="true"></i> {{ $demand->subject }} @if($demand->belongsToMe()) @endif
                                                            @else
                                                                <i class="fa fa-bullseye text-danger" aria-hidden="true"></i> {{ $demand->subject }} @if($demand->belongsToMe()) @endif
                                                            @endif

                                                        @endif {{-- end demand belongs to me --}}
                                                    </h3>
                                                    <div class="card-options">
                                                        {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                                                        @if($demand->hasUUID())
                                                        <a href="{{ route('demands.show.uuid', $demand->uuid) }}" class="btn btn-sm btn-info float-right"><i class="fa fa-eye" aria-hidden="true"></i> Vezi detalii complete (uuid)</a>
                                                        @else
                                                        <a href="{{ route('demands.show', $demand) }}" class="btn btn-sm btn-info float-right"><i class="fa fa-eye" aria-hidden="true"></i> Vezi detalii complete (normal)</a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            <p class="small"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $demand->showPublishDate() }}</p>
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

                                                                @if($demand->belongsToMe())
                                                                    <span class="badge badge-success" style="font-weight:100;"><i class="side-menu__icon ti-user"></i> Proprietar</span>
                                                                @else
                                                                <p>Status: 
                                                                    @if($demand->hasProfessional(auth()->user()->professional))
                                                                        <span class="badge badge-success" style="font-weight:100;"><i class="side-menu__icon ti-unlock"></i> Deblocata</span>
                                                                    @else
                                                                        <span class="badge badge-danger" style="font-weight:100;"><i class="side-menu__icon ti-lock"></i> Blocata</span>
                                                                    @endif
                                                                </p>
                                                                @endif {{-- end demand belongs to me --}}
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                {{-- <a href="{{ route('demands.show', $demand) }}" class="btn btn-sm btn-default"><i class="fa fa-thumbs-down"></i> Nu ma intereseaza</a> --}}
                                                                <p>Raportari: {{ $demand->reports->count() }}</p>
                                                            </div>

                                                            <div class="d-flex justify-content-end">
                                                                <p class="mt-2">
                                                                    @if($demand->getStatus() == 1) <span class="badge badge-success">Verificata</span> @elseif($demand->getStatus() == 0) <span class="badge badge-default">Neverificata</span> @else <span class="badge badge-danger">Falsa</span> @endif
                                                                </p>
                                                            </div>

                                                            <div class="d-flex justify-content-end">
                                                                <p class="mt-2">
                                                                    Stare: @if($demand->getState() == 1) <span class="badge badge-success">Activa</span> @else <span class="badge badge-default">Inactiva</span> @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                    {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                                                </div>
                                            </div>
                                            @endif
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
			
	
	

		