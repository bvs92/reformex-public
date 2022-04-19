@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii proiecte</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item"><a href="{{ route('work-project-categories.index') }}">Categorii proiecte</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
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
                            <h3 class="card-title ">Detalii categorie</h3>
                            <div class="card-options">
                                {{-- <button id="delete_category" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Elimina categoria</button> --}}
                                <delete-single-project-category-component :the_category="{{ json_encode($category) }}"></delete-single-project-category-component>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <edit-single-project-category-component :the_category="{{ json_encode($category) }}"></edit-single-project-category-component>
                            </div>
                        </div>
                    </div>
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->


             <!-- ROW-6 -->
             <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Informații proiecte</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <list-projects-by-category-component :category="{{ json_encode($category) }}"></list-projects-by-category-component>
                            </div>
                        </div>
                    </div>
                </div><!-- COL END -->
            </div><!-- ROW-6 END -->


        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
@endsection
			
	
	

		