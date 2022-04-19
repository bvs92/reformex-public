@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Detalii profesionist</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalii profesionist</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
			

            <!-- start new users list -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header ">
                            <h3 class="card-title ">Detalii despre profesionist</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <user-personal-information-component :the_user="{{ json_encode($user) }}"></user-personal-information-component>
                                </div>
                                <div class="col-lg-6">
                                    <decide-registration-pro-component :the_user="{{ json_encode($user) }}"></decide-registration-pro-component>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <user-company-simple-information-component :user_id="{{ json_encode($user->id) }}"></user-company-simple-information-component>
                                </div>
                            </div>

                            <div class="row">
                                <hr>
                                <div class="col-lg-6">
                                    <user-roles-component :the_user="{{ json_encode($user) }}"></user-roles-component>
                                </div>
                                <div class="col-lg-6">
                                    <user-account-settings-component :the_user="{{ json_encode($user) }}"></user-account-settings-component>
                                </div>
                            </div>

                        </div>
                
                    </div>
                    
                </div><!-- Users List - COL-END -->
            </div><!-- end new users list -->

        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
			
	
	

		