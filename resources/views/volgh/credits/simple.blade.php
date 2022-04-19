@extends('volgh.layouts.master')
@section('css')
@endsection


@section('title-page')
<title>Credit & Cupoane</title>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Credit</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Credit</li>
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
                        <h2 class="card-title ">Detalii credit și listare cupoane</h2>
                        <div class="card-options">
                            <a href="{{ route('coupons.requests') }}" id="request__new__coupon" class="btn btn-md btn-primary"><i class="fa fa-arrow-right"></i> Pagină solicitare cupon</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-2 d-flex justify-content-start">
                                        <h3>Credit: <strong>{{ $amount }} RON</strong> @if($amount < 30) <span class="text-small" style="font-size:14px;">Solicită un nou cupon. <a href="{{ route('coupons.requests') }}" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> Pagină solicitare cupon</a></span> @endif</h3>
                                    </div>
                                    <hr>
                                </div>
                            </div> <!-- end row -->

                            <list-personal-coupons-component></list-personal-coupons-component>
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
			
	
	

		