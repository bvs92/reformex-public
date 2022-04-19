@extends('volgh.layouts.master')
@section('css')
{{-- <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" /> --}}
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Detalii banner</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalii banner</li>
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
                            <h3 class="card-title ">Detalii banner</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                @if(session()->has('message_payment'))
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check-circle-o mr-2" aria-hidden="true"></i> <strong>Felicitări!</strong> {{ session()->get('message_payment') }}
                                </div>
                                @elseif(session()->has('message_payment_expired'))
                                <div class="alert alert-warning" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {{ session()->get('message_payment_expired') }}
                                </div>
                                @endif
                                <show-single-personal-banner-component :uuid="{{ json_encode($uuid) }}"></show-single-personal-banner-component>
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
{{-- <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script> --}}
@endsection
			
	
	

		