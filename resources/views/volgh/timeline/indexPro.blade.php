@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Conversatii cu clienti</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Conversatii cu clienti</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				

            <!-- ROW-4 -->
            {{-- <div class="row">
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
                                <h3 class="number-font mb-1">#</h3>
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
                                    <h6 class="mb-3">Cheltuieli cotatii</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            <h3 class="number-font mb-1"># RON</h3>
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
            </div> --}}
            <!-- ROW-4 END -->

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h2 class="card-title ">Lista conversatii cu clienti ({{ $total_timelines }})</h2>
                            <div class="card-options">
                                {{-- <a href="#" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Cotatie noua</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    @forelse($timelines as $timeline)   
                                        @if($timeline->demand)
                                        <div class="card mt-2">
                                            <div class="card-body">
                                                <h3 class="card-title mb-5"><span class="text-muted">Conversatie: </span>Eu <i class="fa fa-exchange text-success"></i> {{ $timeline->demand->user->getName() }} <i class="fa fa-long-arrow-right"></i> {{ $timeline->demand->subject }} <span class="text-muted">(Numar cerere #{{ $timeline->demand->id }})</span></h3>
                                                {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                                            
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <p>ID: <strong>{{ $timeline->getDisponibleId() }}</strong> </p>

                                                        {{-- <p>Cotatii primite: <span class="badge badge-secondary ">
                                                            #    
                                                        </span></p> --}}

                                                        @if($timeline->demand->categories && $timeline->demand->categories->count() > 0)
                                                        <p><i class="fa fa-tags" aria-hidden="true"></i>
                                                            @foreach($timeline->demand->categories as $category)
                                                                <span>{{ $category->name }}</span>
                                                                @if(!$loop->last)
                                                                <span>| </span> 
                                                                @endif
                                                            @endforeach
                                                        </p>
                                                        @endif

                                                        {{-- <p>Categorie: <strong>{{ $timeline->demand->firstCategory() }}</strong>  
                                                        </span></p> --}}

                                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ ucfirst($timeline->demand->city) }}</strong> </p>

                                                        <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatCarbonDate($timeline->demand->created_at) }}</p>

                                                    </div>


                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <p>Cost: <strong>{{ $timeline->demand->getCalculatedPriceNormal() }} RON</strong> </p>
                                                        <p>Demarare proiect: <span class="text-success">Urgent</span></p>

                                                        @if($timeline->deleteFromClient() == 1)
                                                            <p class="text-danger">
                                                                <i class="ti-trash"></i> Clientul a eliminat aceasta conversatie.
                                                            </p>
                                                        @endif

                                                        @if($timeline->hasUUID())
                                                        <a href="{{ route('timeline.show.pro.uuid', $timeline->uuid) }}" class="btn btn-sm btn-primary">Vezi conversatie</a>
                                                        @else
                                                        <a href="{{ route('timeline.show.pro', $timeline->id) }}" class="btn btn-sm btn-primary">Vezi conversatie</a>
                                                        @endif

                                                        @if($timeline->demand->hasUUID())
                                                        <a href="{{ route('demands.show.uuid', $timeline->demand->uuid) }}" class="btn btn-sm btn-secondary">Vezi cerere</a>
                                                        @else
                                                        <a href="{{ route('demands.show', $timeline->demand->id) }}" class="btn btn-sm btn-secondary">Vezi cerere</a>
                                                        @endif
                                                    </div>
                                                </div>
                                
                                                <div class="row mt-2">
                                                    <div class="col-lg-6 col-md-6">
                                                        
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="card mt-2">
                                            <div class="card-body">
                                                <h3 class="card-title mb-5"><span class="text-muted">Conversatie #{{ $timeline->getDisponibleId() }}: </span><span class="text-danger text-sm"><i class="ti-na"></i> Cerere eliminata</span></h3>
                                                {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                                            
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <p>ID: <strong>{{ $timeline->getDisponibleId() }}</strong> </p>
                                                        <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatCarbonDate($timeline->created_at) }}</p>

                                                    </div>


                                                    <div class="col-lg-6 col-md-6 col-sm-12">

                                                        @if($timeline->hasUUID())
                                                        <a href="{{ route('timeline.show.pro.uuid', $timeline->uuid) }}" class="btn btn-sm btn-primary">Vezi conversatie</a>
                                                        @else
                                                        <a href="{{ route('timeline.show.pro', $timeline->id) }}" class="btn btn-sm btn-primary">Vezi conversatie</a>
                                                        @endif
                                                    </div>
                                                </div>
                                
                                                <div class="row mt-2">
                                                    <div class="col-lg-6 col-md-6">
                                                        
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @empty
                                        <p class="text-center m-5">Nu exista cotatii de pret inregistrate.</p>
                                    @endforelse
                                    <div class="d-flex justify-content-center my-4">
                                        {{ $timelines->links() }}
                                    </div>
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
			
	
	

		