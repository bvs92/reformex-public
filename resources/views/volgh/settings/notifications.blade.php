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
                                        @if(auth()->user()->hasProfilePhoto())
                                            <img src="{{ asset(auth()->user()->getFullProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="userpicimg">
                                        @else
                                            <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="" class="userpicimg"> 
                                        @endif
                                    </div>
                                    <h3 class="username text-dark mb-2">{{ auth()->user()->getName() }}</h3>
                                    <p class="mb-1 text-muted">@if(auth()->user()->hasRoles()) {{ ucfirst(auth()->user()->getFirstRole()->name) }} @endif @if(auth()->user()->isPro()) | {{ auth()->user()->professional->getLocation() }} @endif</p>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                            Setari notificari
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- <ul class="list-group">
                                <li class="list-group-item">
                                    Notificari prin e-mail
                                    <div class="material-switch pull-right">
                                        <input id="someSwitchOptionSuccess1" name="someSwitchOption001" type="checkbox">
                                        <label for="someSwitchOptionSuccess1" class="label-success"></label>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    Notificari prin SMS
                                    <div class="material-switch pull-right">
                                        <input id="someSwitchOptionSuccess2" name="someSwitchOption001" type="checkbox">
                                        <label for="someSwitchOptionSuccess2" class="label-success"></label>
                                    </div>
                                </li>
                            </ul> --}}

                            <user-notification-settings-component></user-notification-settings-component>
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
{{-- <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script> --}}
@endsection

@section('footer-scripts')


@endsection