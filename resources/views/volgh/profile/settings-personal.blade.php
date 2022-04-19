
@extends('volgh.layouts.master')

@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('css')
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}

<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
@endsection


@section('title-page')
<title>Setari Cont & Profil</title>
@endsection


@section('page-header')
<!-- PAGE-HEADER -->
	<div>
		<h1 class="page-title">Setări Cont și Profil</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Acasă</a></li>
			<li class="breadcrumb-item active" aria-current="page">Setări Cont și Profil</li>
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
												<simple-profile-photo-component></simple-profile-photo-component>

												<h3 class="username text-dark mb-2">{{ auth()->user()->getName() }}</h3>
												@if(auth()->user()->isPro())
													@if(auth()->user()->company)
														@if(auth()->user()->company->location)
														<p class="mb-1 text-muted">{{ auth()->user()->company->location->value }}</p>
														@endif
													@endif

                                                    {{-- @if(auth()->user()->professional->reviews && auth()->user()->professional->reviews->count() > 0)
														<div class="wideget-user-rating" data-toggle="tooltip" data-placement="top" title="{{ auth()->user()->professional->reviews->count() }} @if(auth()->user()->professional->reviews->count() == 1) Recenzie @else Recenzii @endif">
															@for($i = 1; $i <= 5; $i++)
																@if($i <= auth()->user()->professional->getRating())
																	<a href="#"><i class="fa fa-star text-warning"></i></a>
																@else
																	<a href="#"><i class="fa fa-star-o text-warning mr-1"></i></a> 
																@endif
															@endfor
															<span>{{ auth()->user()->professional->getRating() }} din 5 stele</span>
														</div>
													@endif --}}

													@if(auth()->user()->badge)
														@if(auth()->user()->badge->verified == true)
														<img src="{{ asset('assets/images/badges/firma-verificata.png') }}" alt="firma verificata" class="avatar avatar-xl cover-image mt-4" style="background: none!important;">
															<p class="text-center" style="text-transform: uppercase; font-size: 14px;padding: 10px; font-weight: bold;color:#afafaf;">Firma verificata</p>
														@else
															<p class="text-center" style="text-transform: uppercase; font-size: 14px;padding: 10px; font-weight: bold;color:#afafaf;"><i class="fa fa-times-circle-o pr-1" style="color:grey;"></i>Firma neverificata</p>
														@endif
													@endif

												@endif
											</div>
										</div>
									</div>
								</div>

							</div>






							<div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

								<div class="card">
									<div class="card-header">
										<div class="card-title">Setări profil utilizator</div>
									</div>
									<div class="card-body">
										<!-- start card --><div class="mb-4">
											<edit-personal-information-component :the_user="{{ json_encode(auth()->user()) }}"></edit-personal-information-component>
										</div><!-- end card -->

										<!-- start card --><div class="mb-4">
											<edit-profile-photo-component></edit-profile-photo-component>
										</div><!-- end card -->


										<!-- start card --><div class="mb-4">
											<edit-password-component></edit-password-component>
										</div><!-- end card -->

										<!-- start card --><div class="mb-4">
											<deactivate-personal-account-component></deactivate-personal-account-component>
										</div><!-- end card -->

										<!-- start card -->
										{{-- <div class="mb-4">
											<delete-account-component></delete-account-component>
										</div> --}}
										<!-- end card -->


										<!-- start card -->
										{{-- <div class="">
											<edit-social-profiles-component></edit-social-profiles-component>
										</div> --}}
										<!-- end card Profil SOcial -->
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



{{-- <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script> --}}

@endsection