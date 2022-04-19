@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Raportari cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Raportari cereri</li>
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
                            <h3 class="card-title ">Cereri raportate</h3>
                            <div class="card-options">
                                {{-- <a href="{{ route('categories.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga categorie noua</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="table-responsive">

                                        <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                            <thead class="thead-dark">
                                                <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Denumire (#ID)</th>
                                                <th scope="col">Profesionist</th>
                                                <th scope="col">Publicare</th>
                                                <th scope="col">Actiuni</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($reports && $reports->count() > 0)
                                                    @foreach($reports as $report)
                                                        <tr>
                                                            <th scope="row">{{ $report->id }}</th>
                                                            @if($report->demand)
                                                                <td style="width:40%;">{{ $report->demand->subject }} (#{{ $report->demand->id }})</td>
                                                            @else
                                                                <td style="width:40%;">{{ $report->demand_id }} (Cerere eliminata)</td>
                                                            @endif
                                                            <td>
                                                                @if($report->user->isPro())
                                                                    {{ $report->user->professional->getName() }} {{ $report->user->id == auth()->user()->id ? "(Eu)" : '' }}
                                                                @else
                                                                    {{ $report->user->getName() }}
                                                                @endif
                                                            </td>
                                                            <td>{{ formatCarbonDate($report->created_at) }}</td>
                                                            <td>
                                                                <div class="row">
                                                                    <a href="{{ route('demands_reports.show', $report->id) }}" class="btn btn-info btn-sm m-1">Vezi</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            </table>
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
			
	
	

		