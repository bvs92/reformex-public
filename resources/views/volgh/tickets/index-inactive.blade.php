@extends('volgh.layouts.master-inactive')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Tichete</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tichete</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				
<!-- ROW-5 -->
<div class="row">
    <div class="col-12 col-sm-12">
        <list-personal-tickets-component></list-personal-tickets-component>
    </div><!-- COL END -->
</div>
<!-- ROW-5 END -->

            
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
			
	
	

		