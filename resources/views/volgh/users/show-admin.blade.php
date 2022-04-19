@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Utilizatori</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Utilizatori</li>
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
                            <h3 class="card-title ">Detalii utilizator</h3>
                            <div class="card-options">
                                {{-- <add-new-user-component></add-new-user-component> --}}
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- <list-users-component></list-users-component> --}}

                            {{-- <div class="row">
                                <div class="col-lg-6">
                                    <user-personal-information-component :the_user="{{ json_encode($user) }}"></user-personal-information-component>
                                  </div>
                            
                                <div class="col-lg-6">
                                    <change-user-password-component :the_user_id="{{ json_encode($user->id) }}"></change-user-password-component>
                                    <user-roles-component :the_user="{{ json_encode($user) }}"></user-roles-component>
                                    <user-account-settings-component :the_user="{{ json_encode($user) }}"></user-account-settings-component>
                                </div>
                            </div>  --}}
                            <!-- end row -->

                            <div class="tab_wrapper first_tab">
                                <ul class="tab_list">
                                    <li class="active" rel="tab_1_1">Profil</li>
                                    <li rel="tab_1_2" class="">Firmă</li>
                                    <li rel="tab_1_3" class="">Setări cont</li>
                                    {{-- <li rel="tab_1_4" class="">Cereri lansate</li> --}}
                                    <li rel="tab_1_5" class="">Cereri deblocate</li>
                                    <li rel="tab_1_6" class="">Cupoane</li>
                                    <li rel="tab_1_7" class="">Facturare</li>
                                    <li rel="tab_1_8" class="">Activitate</li>
                                    @if($user->isPro())
                                    <li rel="tab_1_9" class="">Recenzii</li>
                                    @endif
                                </ul>

                                <div class="content_wrapper">
                                    <div title="tab_1_1" class="accordian_header tab_1_1 active"></div>
                                    <div class="tab_content first tab_1_1 active" title="tab_1_1" style="display: block;">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <user-personal-information-component :the_user="{{ json_encode($user) }}"></user-personal-information-component>
                                            </div>
                                            <div class="col-lg-6">
                                                <user-pro-account-component :the_user="{{ json_encode($user) }}"></user-pro-account-component>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div title="tab_1_2" class="accordian_header tab_1_2 undefined"></div>
                                    <div class="tab_content tab_1_2" title="tab_1_2" style="display: none;">
                                        <company-status-component :the_user="{{ json_encode($user) }}"></company-status-component>
                                        <user-company-information-component :user_id="{{ json_encode($user->id) }}"></user-company-information-component>
                                    </div>

                                    <div title="tab_1_3" class="accordian_header tab_1_3 undefined"></div>
                                    <div class="tab_content tab_1_3" title="tab_1_3" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <change-user-password-component :the_user_id="{{ json_encode($user->id) }}"></change-user-password-component>
                                                <user-roles-component :the_user="{{ json_encode($user) }}"></user-roles-component>
                                            </div>
                                            <div class="col-lg-6">
                                                <user-account-settings-component :the_user="{{ json_encode($user) }}"></user-account-settings-component>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div title="tab_1_4" class="accordian_header tab_1_4 undefined"></div>
                                    {{-- <div class="tab_content tab_1_4" title="tab_1_4" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <list-user-demands-component :the_user_id="{{ json_encode($user->id) }}"></list-user-demands-component>
                                            </div>
                                         
                                        </div>
                                    </div> --}}
                                    
                                    <div title="tab_1_5" class="accordian_header tab_1_5 undefined"></div>
                                    <div class="tab_content tab_1_5" title="tab_1_5" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <list-user-unlocked-demands-component :the_user_id="{{ json_encode($user->id) }}"></list-user-unlocked-demands-component>
                                            </div>
                                         
                                        </div>
                                    </div>

                                    <div title="tab_1_6" class="accordian_header tab_1_6 undefined"></div>
                                    <div class="tab_content tab_1_6" title="tab_1_6" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <admin-list-user-coupons-component :user_id="{{$user->id}}"></admin-list-user-coupons-component>
                                            </div>
                                         
                                        </div>
                                    </div>

                                    <div title="tab_1_7" class="accordian_header tab_1_7 undefined"></div>
                                    <div class="tab_content tab_1_7" title="tab_1_7" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <admin-list-user-credit-component :user_id="{{$user->id}}"></admin-list-user-credit-component>
                                            </div>
                                         
                                        </div>
                                    </div>

                                    <div title="tab_1_8" class="accordian_header tab_1_8 undefined"></div>
                                    <div class="tab_content tab_1_8" title="tab_1_8" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <admin-list-user-activity-component :user_id="{{ $user->id }}"></admin-list-user-activity-component>
                                            </div>
                                         
                                        </div>
                                    </div>

                                    <div title="tab_1_9" class="accordian_header tab_1_9 undefined"></div>
                                    <div class="tab_content tab_1_9" title="tab_1_9" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <admin-list-user-reviews-component :user_id="{{ $user->id }}"></admin-list-user-reviews-component>
                                            </div>
                                         
                                        </div>
                                    </div>
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
<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>
{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}
@endsection
			
	
	

		