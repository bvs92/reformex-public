@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri raportate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('demands_reports.index') }}">Cereri raportate</a></li>
                @if($report->demand)
                <li class="breadcrumb-item active" aria-current="page">Cerere #{{ $report->demand->id }}</li>
                @else
                <li class="breadcrumb-item active" aria-current="page">Cerere #{{ $report->demand_id }}</li>
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
                    <div class="card-header ">
                        <h2 class="card-title ">Numar raport: #{{ $report->id }}</h2>
                        <div class="card-options">
                            {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                            <div class="dropdown float-right">
                                <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti-more"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                   
                                    {{-- <a onclick="event.preventDefault();document.getElementById('relauchDemand').submit();" class="dropdown-item"><i class="ti-reload"></i> Relanseaza</a> --}}
                                    {{-- <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a> --}}
                                   
                                    {{-- <form 
                                        action="{{ route('demands.destroy', $demand->id) }}" 
                                        id="deleteDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form> --}}

                                    {{-- <form 
                                        action="{{ route('demands.relaunch', $demand->id) }}" 
                                        id="relauchDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form> --}}

                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <div class="">
                                <div class="row justify-content-center p-4">
                                    <div class="col-md-6 p-4" style="background: white;">


                                        <div class="row my-2">
                                            @if($report->demand)
                                            <div class="col-lg-12">
                                                <p><strong>{{ $report->demand->subject }}</strong></p>
                                            </div>
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($report->demand->created_at) }}</p>
                                            </div>
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon fa fa-user"></i> {{ $report->demand->name}}</p>
                                            </div>

                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon fa fa-at"></i> {{ $report->demand->email}}</p>
                                            </div>

                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon fa fa-phone"></i> {{ $report->demand->phone}}</p>
                                            </div>

                                            <div class="col-lg-6">
                                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ ucfirst($report->demand->city) }}</strong> </p>
                                            </div>
                                            
                                            <hr>
                                            <div class="col-lg-12">
                                                <p>Mesaj cerere:</p>
                                                <p><strong>{{ $report->demand->message }}</strong></p>
                                            </div>

                                            <div class="col-lg-12">
                                                <hr>
                                                <a href="{{ route('demands.show', $report->demand) }}" class="btn btn-sm btn-info">Detalii complete despre cerere</a>
                                            </div>
                                            @else
                                            <div class="col-lg-12">
                                                Aceasta cerere a fost eliminata de catre proprietar.
                                            </div>
                                            @endif
                                        </div>

                                      

                                    </div><!-- end col-lg-8 -->
                                
                                    <div class="col-md-6 p-4">
                                        <div class="row my-2">
                                            <div class="col-lg-6 d-flex justify-content-start">
                                                <p>Status: 
                                                    @if($report->status == '0')
                                                        <span class="badge badge-warning mr-1 mb-1 mt-1 small">In curs</span>
                                                    @else
                                                        <span class="badge badge-success mr-1 mb-1 mt-1">Terminat</span>
                                                    @endif
                                                </p>
                                            </div>
                                          
                                            <div class="col-lg-6 d-flex justify-content-end">
                                                <div class="dropdown float-right">
                                                    <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actiuni
                                                    </a>
                    
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    
                                                        <a onclick="event.preventDefault();document.getElementById('markComplete').submit();" class="dropdown-item"><i class="ti-check"></i> Marcare ca 'Terminat'</a>
                                                        {{-- <a onclick="event.preventDefault();document.getElementById('markClose').submit();" class="dropdown-item"><i class="ti-na"></i> Marcare ca 'Inchis'</a> --}}
                                                        {{-- <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a> --}}
                                                    
                                                        <form 
                                                            action="{{ route('demands_reports.complete', $report->id) }}" 
                                                            id="markComplete" 
                                                            method="POST" 
                                                            style="display: none;">
                                                            @csrf
                                                            @method('PUT')
                                                        </form>

                                                        {{-- <form 
                                                            action="{{ route('demands_reports.close', $report->id) }}" 
                                                            id="markClose" 
                                                            method="POST" 
                                                            style="display: none;">
                                                            @csrf
                                                            @method('PUT')
                                                        </form> --}}
                    
                                                        {{-- <form 
                                                            action="{{ route('demands.relaunch', $demand->id) }}" 
                                                            id="relauchDemand" 
                                                            method="POST" 
                                                            style="display: none;">
                                                            @csrf
                                                            @method('PUT')
                                                        </form> --}}
                    
                                                    </div>
                    
                    
                                                </div>
                                            </div>

                                            @if($report->demand)
                                            <div class="col-lg-12">
                                                @if($report->status !== 0)
                                                    @if($report->demand->getStatus() == 1)
                                                        <p class="alert alert-info text-center small">Cererea nu este falsa. Proprietarul a fost contactat de catre Reformex la cererea dumneavoastra si a fost confirmat. Multumim pentru intelegere.</p>
                                                    @elseif($report->demand->getStatus() == 2)
                                                        <p class="alert alert-success text-center small">Cererea este falsa. Suma de {{ $report->demand->getCalculatedPriceNormal() }} RON v-a fost restituita. Va multumim pentru implicare!</p>
                                                    @endif
                                                @endif
                                                <p>Cost cerere: <strong>{{ $report->demand->getCalculatedPriceNormal() }} RON</strong></p>
                                            </div>
                                            @else
                                            <div class="col-lg-12">
                                                <p class="text-center text-danger">Aceasta cerere a fost eliminata de catre proprietar.</p>
                                            </div>
                                            @endif
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($report->created_at) }}</p>
                                                <p>ID utilizator: {{ $report->user->id }}</p>
                                            </div>
                                            <div class="col-lg-6">
                                                @if($report->user->isPro())
                                                <p><i class="side-menu__icon fa fa-user"></i> {{ $report->user->professional->getName()}}</p>
                                                @else
                                                <p><i class="side-menu__icon fa fa-user"></i> {{ $report->user->getName()}}</p>
                                                @endif
                                                <p>E-mail: {{ $report->user->email }}</p>
                                            </div>
                                            
                                            <div class="col-lg-12">
                                                <hr>
                                                <p>Mesaj:</p>
                                                <p><strong>{{ $report->message }}</strong></p>
                                            </div>

                                        </div>

                                        

                                        
                                        {{-- <div class="row">
                                            Adaugare tichet?
                                            <div class="col-lg-12">
                                                <p>Conversatie asociata: </p>
                                                <ul class="list-group">
                                                    <li class="listunorder1" style="height:45px;">
                                                        Tichet asociat: #1, Status tichet:  
                                                        {{-- @if($demand->ticket->status == 0) --}}
                                                            {{-- <span class="badge badge-success">Deschis</span> --}}
                                                        {{-- @else
                                                            <span class="badge badge-danger">Inchis</span>
                                                        @endif --}}
                                                        {{-- <a href="{{ route('tickets.show', $demand->ticket) }}" class="btn-default btn-sm float-right">Vezi</a> --}}
                                                        {{-- <a href="" class="btn-default btn-sm float-right">Vezi</a> --}}
                                                    {{-- </li> --}}
                                                {{-- </ul> --}}
                                            {{-- </div> --}}
                                        {{-- </div><!-- end row --> --}}
                                       
                                        
                                    </div><!-- end col-lg-6 -->
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

@endsection
			
	
	

		