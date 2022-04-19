@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri de restituire</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cereri de restituire</li>
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
                                    <h6 class="mb-3">Numar categorii</h6>
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
                                    <h6 class="mb-3">Numar cereri</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">#</h3>
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
                                <h3 class="number-font mb-1">8963</h3>
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


            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Detalii returnari clienti</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="p-2 d-flex justify-content-start">
                                                <h3>Balanta returnari: <strong># RON</strong></h3>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                             <div class="p-2 d-flex justify-content-end">
                                             {{-- <p>Ultima plata: @if($last_payment) {{ $last_payment->getReadableAmount() }} RON pe {{ formatCarbonDate($last_payment->created_at) }} @else {{ $last_payment }}@endif.</p> --}}
                                             </div>
                                        </div>

                                    </div> <!-- end row -->


                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->

                </div><!-- COL END -->
            </div><!-- ROW-5 END -->



            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">

                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Cereri de restituire plati</h3>
                            <div class="card-options">
                                {{-- <a href="{{ route('users.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga credit</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="table-responsive">

                                        <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                            <thead>
                                              <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">ID Plata</th>
                                                <th scope="col">Utilizator</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Data</th>
                                                <th scope="col"></th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                @if($refunds_demands)
                                                    @if($refunds_demands->count() > 0)
                                                        @foreach($refunds_demands as $demand)
                                                        <tr>
                                                            <th scope="row"># {{ $demand->id }}</th>
                                                            <td>{{ $demand->payment_intent_id }}</td>
                                                            <td>{{ $demand->user->getName() }}</td>
                                                            <td>
                                                                @if($demand->status == '1')
                                                                    <span class="badge badge-success  mr-1 mb-1 mt-1">Restituit</span>
                                                                @elseif($demand->status == '2')
                                                                    <span class="badge badge-danger  mr-1 mb-1 mt-1">Refuzat</span>
                                                                @elseif($demand->status == '0')
                                                                    <span class="badge badge-warning  mr-1 mb-1 mt-1">In curs</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ formatCarbonDate($demand->created_at) }}</td>
                                                            <td>
                                                                <a href="{{ route('refundsdemands.show', $demand->id) }}" class="btn btn-sm btn-cyan">Vezi</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="4">
                                                                <p class="text-center">Nu exista tranzactii inregistrate.</p>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            </tbody>
                                        </table>

                                        @if($refunds_demands->count() >= 10)
                                            <div class="d-flex justify-content-center mt-6">
                                                {{ $refunds_demands->links() }}
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->


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
			
	
	

		