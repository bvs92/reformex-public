@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Anunțuri globale</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Anunțuri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection


@section('title-page')
<title>Anunțuri globale</title>
@endsection

@section('content')
				

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h2 class="card-title ">Anunțuri globale</h2>
                            <div class="card-options">
                                {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                                <admin-create-announcement-component></admin-create-announcement-component>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <admin-list-announcements-component></admin-list-announcements-component>
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
			
	
	

		