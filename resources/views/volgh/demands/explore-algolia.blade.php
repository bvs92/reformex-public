@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')

@endsection


@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Exploreaza Cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exploreaza Cereri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				
        <explore-demands-algolia></explore-demands-algolia>
        
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
            

@section('footer-scripts')


@endsection
	
	

		