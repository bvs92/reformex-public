@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Recenzii firme</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Recenzii firme</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection


@section('title-page')
<title>Recenzii firme</title>
@endsection

@section('content')
				

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h2 class="card-title ">Recenzii utilizatori tip firmă</h2>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <admin-list-company-reviews-component></admin-list-company-reviews-component>
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
			
	
	

		