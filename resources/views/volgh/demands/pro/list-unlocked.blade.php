@extends('volgh.layouts.master')
@section('css')
@endsection

@section('title-page')
<title>Cereri deblocate</title>
@endsection


@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri deblocate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cereri deblocate</li>
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
                            <h2 class="card-title ">Cereri deblocate</h2>
                            <div class="card-options">
                                {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <pro-list-unlocked-demands-component></pro-list-unlocked-demands-component>
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

@endsection
			
	
	

		