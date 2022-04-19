@extends('volgh.layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">

<style>
.img-thumbnail {
   height: 160px;
}

</style>

@endsection

@section('head-scripts')
  
@endsection


@section('title-page')
<title>Detalii proiect #{{ $current_project->uuid }}</title>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Proiecte</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item"><a href="{{ route('work-projects.personal') }}">Proiecte</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proiect</li>
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
                            <h3 class="card-title ">Proiect #{{ $current_project->uuid }}</h3>
                            <div class="card-options">
                                <delete-single-project-component :project="{{ json_encode($current_project) }}"></delete-single-project-component>
                            </div>
                        </div>
                        <div class="card-body">
                            <show-single-project-component :the_project="{{ json_encode($current_project) }}"></show-single-project-component>
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
@endsection


@section('footer-scripts')


@endsection
			
	
	

		