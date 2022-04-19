@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index.vue') }}">Categorii</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalii categorie</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <single-category-component :the_category="{{ json_encode($category) }}"></single-category-component>
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
@endsection
			
	
	

		