@extends('volgh.layouts.master')

@section('head-scripts')

@endsection


@section('css')

@endsection

@section('title-page')
<title>Setări profil companie</title>
@endsection



@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Informații companie</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Acasă</a></li>
            <li class="breadcrumb-item active" aria-current="page">Informații companie</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Informații companie</div>
                        </div>
                        {{-- <div class="card-body">
                            <cif-company-information :company_info="{{ json_encode($company) }}"></cif-company-information>
                        </div> --}}
                        <div class="card-body">
                            <company-information-component :user_id="{{ json_encode(auth()->user()->id) }}"></company-information-component>
                        </div>
                    </div>
                </div>


               
            </div>
            <!-- ROW-1 CLOSED -->



        </div>
    </div>
    <!--CONTAINER CLOSED -->
</div>
@endsection
@section('js')

@endsection

@section('footer-scripts')


@endsection