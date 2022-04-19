@extends('volgh.layouts.master')
@section('css')
@endsection


@section('title-page')
<title>Chei API</title>
@endsection

@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Chei API</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Acasă</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chei API</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')


<!-- ROW-5 -->
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card ">
            <div class="card-header">
                <h3 class="card-title mb-0">Chei api</h3>
            </div>
            <div class="card-body">
                <div class="grid-margin">
                    <div class="">
                        <api-keys-component></api-keys-component>
                    </div>
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


			
	
	

		