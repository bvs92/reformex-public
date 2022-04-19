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
                
                @if(auth()->user()->hasRole('standard') || auth()->user()->hasRole('professional') && !auth()->user()->isPro())
                <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

                    <div class="row">

                        <div class="card">
                            <div class="card-body text-center row justify-content-center">
                                <h2>Activati modulul de firma pentru a accesa aceasta sectiune.</h2>
                                <form action="{{ route('professionals.activate', auth()->user()->id) }}" method="POST" class="col-lg-8">
                                    @csrf
                                    {{-- <div class="form-group">
                                        <label for="name">Denumire firma</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Denumire firma">
                                    </div> --}}
                            
                                    <button class="btn btn-primary">Activeaza modul firma</button>
                                </form>
                            </div>
                        </div>
                    </div><!-- end of is PRO -->

                </div>
                @else
                
                <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

                    <!-- start tabs --><div class="panel panel-primary bg-white">
									<div class="tab-menu-heading">
										<div class="tabs-menu">
											<!-- Tabs -->
											<ul class="nav panel-tabs">
												<li><a href="#tab1" class="active" data-toggle="tab">Modul profesionist</a></li>
												<li><a href="#tab2" data-toggle="tab">Informatii firma</a></li>
											</ul>
										</div>
									</div>
									<div class="panel-body tabs-menu-body">
										<div class="tab-content">
											
											<div class="tab-pane active" id="tab1">
												@if($user->isPro())
                                                <!-- start card --><div class="">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Setari modul PROFESIONIST</h3>
                                                    </div>
                                                    <div class="card-body">

                                                        <profile-places-component :appid="{{config('services.algolia.appId')}}" :appkey="{{config('services.algolia.apiKey')}}" :location="{{ json_encode(auth()->user()->professional) }}" :the_accessTokenMap="{{ json_encode(config('services.mapbox.api_key')) }}"></profile-places-component>
                                                        <hr>

                                                        {{-- <form method="POST" action="{{ route('professionals.updatePro') }}">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-12">
                                                                    @if(auth()->user()->professional->range)
                                                                    <h4>Raza de interventie: {{ auth()->user()->professional->range / 1000 }} km.</h4>
                                                                    @endif

                                                                    <div class="form-group">
                                                                        <label for="pro_name">Distanta</label>
                                                                        <input type="numeric" 
                                                                        class="form-control @error('range') is-invalid @enderror" id="range" 
                                                                        name="range" placeholder="Distanta in km" value="{{ old('range') ?? auth()->user()->professional->range / 1000 }}">
                                                                        @error('range')
                                                                        <p class="small text-danger">{{ $message }}</p>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    @if(auth()->user()->professional->city)
                                                                        <h4>Oras: {{ auth()->user()->professional->city }}.</h4>
                                                                    @endif

                                                                    <div class="form-group">
                                                                        <label for="city">Oras</label>
                                                                        <input type="search" id="address-input" 
                                                                            class="form-control @error('city') has-error @enderror" 
                                                                            placeholder="Care este locatia (orasul) companiei?" 
                                                                            name="city" 
                                                                            value="{{ old('city') ?? auth()->user()->professional->city }}">
                                                                        @error('city')
                                                                                <p class="small text-danger">{{ $message }}</p>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            

                                                            <input type="hidden" id="the-city" name="the-city" value="{{ old('city') ?? auth()->user()->professional->city }}" />
                                                            <input type="hidden" id="administrative" name="administrative" value="{{ old('administrative') ?? auth()->user()->professional->administrative }}" />
                                                            <input type="hidden" id="postal_code" name="postal_code" value="{{ old('postal_code') ?? auth()->user()->professional->postal_code }}" />
                                                            <input type="hidden" id="lat" name="lat" value="{{ old('lat') ?? auth()->user()->professional->lat }}" />
                                                            <input type="hidden" id="lng" name="lng" value="{{ old('lng') ?? auth()->user()->professional->lng }}" />

                                                            <div class="form-row">
                                                                <div class="col">
                                                                    <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                                                                </div>
                                                            </div>


                                                        </form> --}}

                                                        <hr>
                                                        <h4>Selectati categoriile de interes</h4>
                                                        <categories-profile-component :inc_categories="{{ json_encode($categories) }}" :my_categories="{{ json_encode($my_categories) }}"></categories-profile-component>

                                                    </div>
                                                </div><!-- end card -->
                                                @endif {{-- end if PRO --}}
											</div>
											<div class="tab-pane" id="tab2">

                                                <company-profile-component :company_info="{{ json_encode($company) }}"></company-profile-component>
											</div>
										</div>
									</div>
								</div><!-- end tabs -->


                </div>
                @endif
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