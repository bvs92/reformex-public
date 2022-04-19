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
				

            <!-- ROW-4 -->
            <div class="row">
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
                                <h3 class="number-font mb-1">{{ $quotes->count() }}</h3>
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
                            <h3 class="number-font mb-1">{{ $activity_spent }} RON</h3>
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
                            <h2 class="card-title ">Lista cotatii trimise</h2>
                            <div class="card-options">
                                <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    @forelse($quotes as $quote)   
                                        <div class="card mt-2">
                                            <div class="card-body">
                                                <h3 class="card-title mb-5">Eu <i class="fa fa-exchange text-success"></i> {{ $quote->demand->name }} <i class="fa fa-long-arrow-right"></i> {{ $quote->demand->subject }} (#{{ $quote->demand->id }})</h3>
                                                {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                                            
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <p>Cotatii primite: <span class="badge badge-secondary ">
                                                            {{ $quote->demand->quotesNumber() }}    
                                                        </span></p>

                                                        <p>Categorie: <strong>{{ $quote->demand->firstCategory() }}</strong>  
                                                        </span></p>

                                                        <p>Oras: <strong>{{ ucfirst($quote->demand->city) }}</strong> </p>

                                                        <p>Publicat: {{ $quote->demand->showPublishDate() }}</p>

                                                    </div>


                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <p>Cost: <strong>{{ $quote->demand->getCost() }} RON</strong> </p>
                                                        <p>Demarare proiect: <span class="text-success">Urgent</span></p>

                                                        <a href="{{ route('demands.show', $quote->demand) }}" class="btn btn-sm btn-primary">Vezi conversatie</a>
                                                        <a href="{{ route('demands.show', $quote->demand) }}" class="btn btn-sm btn-secondary">Vezi cerere</a>
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
                                    @empty
                                        <p class="text-center m-5">Nu exista cotatii de pret inregistrate.</p>
                                    @endforelse
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
			
	
	

		