@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Activitate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Activitate</li>
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
                            <h3 class="card-title ">Activitate</h3>
                            <div class="card-options">
                            </div>
                        </div>
                        <div class="card-body">
                            <personal-activities-component></personal-activities-component>
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
@endsection
			
	
	

		