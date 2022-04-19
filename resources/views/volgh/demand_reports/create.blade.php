@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categorii</li>
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
                            <h3 class="card-title ">Raporteaza cerere cu Id #{{ $demand->id }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h5>Subiect cerere: {{ $demand->subject }}</h5>
                                            <p>Proprietar: {{ $demand->name }}</p>
                                            <p>E-mail proprietar: {{ $demand->email }}</p>
                                        </div>
                                        <div class="col-lg-6">
                                            <p>Cost: {{ $demand->getCalculatedPriceNormal() }} RON</p>
                                            <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ ucfirst($demand->city) }}</strong> </p>
                                            <p class="small">Publicat: <i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatCarbonDate($demand->created_at) }} <span>({{ carbonDateToRo($demand->created_at) }})</span></p>
                                        </div>
                                    </div>

                                    <hr>
                                    <form action="{{ route('demands_reports.store', $demand->id) }}" method="POST">
                                        @csrf
                                    
                                        <div class="form-group">
                                            <label for="message">Mesajul dumneavoastra</label>
                                            <textarea class="form-control 
                                            @error('message') has-error @enderror" 
                                            name="message" id="message" cols="30" rows="10">{{ old('message') }}</textarea>
                                            @error('message')
                                                <p class="small text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                            <button type="submit" class="btn btn-success">Raporteaza cerere</button>
                                            </div>
                                        </div>
                                    
                                    
                                    </form>
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


@section('footer-scripts')

@endsection
			
	
	

		