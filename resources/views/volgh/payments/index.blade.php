@extends('volgh.layouts.master')
@section('css')
@endsection

@section('title-page')
<title>Facturare și Plăți</title>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Facturare și Plăți</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Facturare și Plăți</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')


        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header ">
                        <h3 class="card-title ">Date de facturare</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <invoice-information-component></invoice-information-component>
                        </div>
                    </div>
                </div> <!-- end card -->


                <div class="card">
                    <div class="card-header ">
                        <h3 class="card-title ">Plăți</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <payments-personal-component></payments-personal-component>
                        </div><!-- end grid -->
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
			
	
	

		