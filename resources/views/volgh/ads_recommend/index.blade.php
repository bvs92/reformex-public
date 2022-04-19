@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Anunțuri firme recomandate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Anunțuri firme recomadate</li>
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
                            <h3 class="card-title ">Anunțuri firme recomandate</h3>
                            <div class="card-options">
                                <a href="{{ route('advertising.ad_recommend.personal.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adaugă anunț nou</a>
                            </div>                            
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <list-personal-ads-recommend-component></list-personal-ads-recommend-component>
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
			
	
	

		