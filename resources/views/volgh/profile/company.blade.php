@extends('volgh.layouts.master')

@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('css')
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}

<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
@endsection
@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Setari profesionist</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Acasa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Setari profesionist</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-lg-4 col-xl-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="userprofile">
                                    <div class="userpic brround"> 
                                        @if($user->hasProfilePhoto())
                                            <img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" class="userpicimg">
                                        @else
                                            <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="" class="userpicimg"> 
                                        @endif
                                    </div>
                                    <h3 class="username text-dark mb-2">{{ $user->getName() }}</h3>
                                    <p class="mb-1 text-muted">@if($user->hasRoles()) {{ ucfirst($user->getFirstRole()->name) }} @endif @if($user->isPro()) | {{ $user->professional->getLocation() }} @endif</p>
                                    {{-- <div class="text-center mb-4">
                                        <span><i class="fa fa-star text-warning"></i></span>
                                        <span><i class="fa fa-star text-warning"></i></span>
                                        <span><i class="fa fa-star text-warning"></i></span>
                                        <span><i class="fa fa-star-half-o text-warning"></i></span>
                                        <span><i class="fa fa-star-o text-warning"></i></span>
                                    </div> --}}
                                    {{-- <div class="socials text-center mt-3">
                                        <a href="" class="btn btn-circle btn-primary ">
                                        <i class="fa fa-facebook"></i></a> <a href="" class="btn btn-circle btn-danger ">
                                        <i class="fa fa-google-plus"></i></a> <a href="" class="btn btn-circle btn-info ">
                                        <i class="fa fa-twitter"></i></a> <a href="" class="btn btn-circle btn-warning "><i class="fa fa-envelope"></i></a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
               
                
                <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

                    <!-- start tabs -->
                    <div class="panel panel-primary bg-white">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li><a href="#tab2" data-toggle="tab" class="active">Informatii firma</a></li>
                                    <li><a href="#tab3" data-toggle="tab">Informatii firma automatic</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab2">
                                    <company-profile-component :company_info="{{ json_encode($company) }}"></company-profile-component>
                                </div>
                                <div class="tab-pane" id="tab3">
                                    <automatic-company-information :company_info="{{ json_encode($company) }}"></automatic-company-information>
                                </div>
                            </div>
                        </div>
                    </div><!-- end tabs -->


                </div>


               
            </div>
            <!-- ROW-1 CLOSED -->



        </div>
    </div>
    <!--CONTAINER CLOSED -->
</div>
@endsection
@section('js')
{{-- <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script> --}}
@endsection

@section('footer-scripts')



<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>

@endsection