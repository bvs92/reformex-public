@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Balanta</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Balanta</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				


            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Detalii credit & alimentare cont</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="p-2 d-flex justify-content-start">
                                                <h3>Balanta curenta: <strong>{{ $amount }} RON</strong></h3>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                             <div class="p-2 d-flex justify-content-end">
                                             <p>Ultima plata: @if($last_payment) {{ $last_payment->getReadableAmount() }} RON pe {{ formatCarbonDate($last_payment->created_at) }} @else {{ $last_payment }}@endif.</p>
                                             </div>
                                        </div>

                                    </div> <!-- end row -->

                                    
                                    <hr>
                                    <form action="{{ route('credits.add') }}" method="POST">
                                        @csrf
                                    
                                    
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control @error('credit') has-error @enderror" id="credit" placeholder="Suma in ron: 100" name="credit" value="{{ old('credit') }}">
                                                @error('credit')
                                                    <p class="small text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Fake alimentare cont</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->


                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Plati & Costuri</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="row my-4">
                                        <div class="col mx-2">
                                            <div class="row rounded">
                                                <div class="col float-left">
                                                    <h5>Total plati: </h5>
                                                </div>
                                                
                                                <div class="col">
                                                    <h5 class="float-right"><strong>{{ $total_amount }} RON</strong>.</h5>
                                                </div>
                                            </div>
                                        </div><!-- / left side --> 
                                        <div class="col mx-2">
                                            <div class="rounded">
                                                <div class="row px-1">
                                                    <div class="col float-left">
                                                        <h5>Costuri totale: </h5>
                                                    </div>
                                                    <div class="col">
                                                        <h5 class="float-right"><strong>-{{ $total_cost }} RON</strong>.</h5>
                                                    </div>
                                                    {{-- <div style="height: 1px;
                                                    background: black;
                                                    display: block;
                                                    width: 90%;
                                                    margin: 10px auto;"></div> --}}
                                                </div>
                                    
                                                {{-- <div class="row px-1">
                                                    <ul class="list-group list-group-flush" style="    width: 90%;
                                                    margin: 0 auto;">
                                                        <li class="list-group-item">
                                                            <span class="float-left">Taxa contact #122332</span>
                                                            <span class="float-right">-8 RON</span>
                                                        </li>
                                    
                                                        <li class="list-group-item">
                                                            <span class="float-left">Taxa contact #122333</span>
                                                            <span class="float-right">-6 RON</span>
                                                        </li>
                                                    </ul>
                                                </div> --}}
                                    
                                            </div>
                                        </div> <!-- / right-side -->
                                    </div> <!-- / plati -->
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
                            <h3 class="card-title ">Plati</h3>
                            <div class="card-options">
                                <a href="{{ route('users.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga credit</a>
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
                                                <th scope="col">ID</th>
                                                <th scope="col">Suma</th>
                                                <th scope="col">Data</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            @if($payments)
                                                @if($payments->count() > 0)
                                                    @foreach($payments as $payment)
                                                    <tr>
                                                        <th scope="row">#{{ $payment->id }}</th>
                                                        <td>{{ $payment->payment_method_id }}</td>
                                                        <td>+{{ $payment->getReadableAmount() }} RON</td>
                                                        <td>{{ formatCarbonDate($payment->created_at) }}</td>
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

                                        @if($payments->count() >= 10)
                                            <div class="d-flex justify-content-center mt-6">
                                                <a href="#" class="btn btn-default">Vezi toate tranzactiile</a>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->




                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Tranzactii</h3>
                            <div class="card-options">
                                <a href="{{ route('users.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga credit</a>
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
                                                <th scope="col">Data</th>
                                                <th scope="col">Tip</th>
                                                <th scope="col">Suma</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            @if($transactions)
                                                @if($transactions->count() > 0)
                                                    @foreach($transactions as $transaction)
                                                    <tr>
                                                        <th scope="row">#{{ $transaction->id }}</th>
                                                        <td>{{ $transaction->created_at->diffForHumans() }}</td>
                                                        <td>{{ $transaction->description }}</td>
                                                        <td>+{{ $transaction->amount }} RON</td>
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

                                        @if($transactions->count() >= 10)
                                            <div class="d-flex justify-content-center mt-6">
                                                <a href="#" class="btn btn-default">Vezi toate tranzactiile</a>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->




                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Activitate</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="table-responsive">

                                        <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                            <thead>
                                              <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Data</th>
                                                <th scope="col">Tip</th>
                                                <th scope="col">Cerere</th>
                                                <th scope="col">Suma</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                @if($activities)
                                                @if($activities->count() > 0)
                                                    @foreach($activities as $activity)
                                                    <tr>
                                                        <th scope="row">#{{ $activity->id }}</th>
                                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                                        <td>{{ $activity->description }}</td>
                                                        @if($activity->demand)
                                                        <td><a href="{{ route('demands.show', $activity->demand_id) }}">#{{ $activity->demand_id }}</a></td>
                                                        @else
                                                        <td>Cerere #{{ $activity->demand_id }} (eliminata)</td>
                                                        @endif
                                                        <td>-{{ $activity->amount }} RON</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5">
                                                            <p class="text-center">Nu exista tranzactii inregistrate.</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                            </tbody>
                                        </table>

                                        @if($activities->count() >= 10)
                                            <div class="d-flex justify-content-center mt-6">
                                                <a href="#" class="btn btn-default">Vezi toata activitatea</a>
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
			
	
	

		