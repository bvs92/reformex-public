@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')

@endsection

@section('title-page')
<title>Explorează cererile clienților</title>
@endsection


@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Explorează cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Explorează cereri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				
        <explore-demands-final-component :app_id="{{ json_encode(config('services.algolia.appId')) }}" :api_key="{{ json_encode(config('services.algolia.apiKey')) }}"></explore-demands-final-component>
        
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
            

@section('footer-scripts')


@endsection
	
	

		