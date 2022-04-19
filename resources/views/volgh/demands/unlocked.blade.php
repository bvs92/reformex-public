@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri deblocate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cereri deblocate</li>
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
                                    <h6 class="mb-3">Numar cereri deblocate</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">{{ $demands->count() }}</h3>
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
                                <h3 class="number-font mb-1">{{ auth()->user()->professional->quotes->count() }}</h3>
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
                            <h2 class="card-title ">Lista cereri deblocate ({{ $total_demands }})</h2>
                            <div class="card-options">
                                {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    @if($demands)
                                        @foreach($demands as $demand)   
                                            <div class="card mt-2" style="border:none;border-bottom:1px solid #f1f1f1;">
                                                <div class="card-header ">
                                                    <h3 class="card-title ">
                                                        @if($demand->hasProfessional(auth()->user()->professional))
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
                                                        <div class="col-lg-8">
                                                            <div class="d-flex justify-content-start">
                                                                <p> 
                                                                    @if($demand->hasProfessional(auth()->user()->professional))
                                                                        <span class="badge badge-success" style="font-weight:100;"><i class="side-menu__icon ti-unlock"></i> Deblocata</span>
                                                                    @else
                                                                        <span class="badge badge-danger" style="font-weight:100;"><i class="side-menu__icon ti-lock"></i> Blocata</span>
                                                                    @endif
                                                                </p>
                                                            </div>

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
                                                        <div class="col-lg-4">
                                                            <div class="d-flex justify-content-end">
                                                                <p class="small"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatCarbonDate($demand->created_at) }} ({{ carbonDateToRo($demand->created_at) }})</p>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                @if($demand->isReportedBy(auth()->user()))
                                                                <span class="badge badge-default" style="font-weight:100;"><i class="side-menu__icon ti-alert"></i> Cerere raportata. <a href="{{ route('demands_reports.show', $demand->getReportFor(auth()->user())->id) }}" class="btn btn-sm btn-default"><i class="ti-link"></i> Vezi</a></span>
                                                                @endif
                                                                {{-- <a href="{{ route('demands.show', $demand) }}" class="btn btn-sm btn-default"><i class="fa fa-thumbs-down"></i> Nu ma intereseaza</a> --}}
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                    {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="d-flex justify-content-center my-4">
                                            {{ $demands->links() }}
                                        </div>
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
			
	
	

		