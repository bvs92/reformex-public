@extends('volgh.layouts.master-normal')



@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">

@endsection

@section('title-page')
@if($user->company)
<title>Proiecte {{ $user->company->name }} | @if($single_project)#{{ $single_project->uuid }}@endif</title>
@else
<title>Proiecte {{ $user->getName() }} | @if($single_project)#{{ $single_project->uuid }}@endif</title>
@endif
@endsection

@section('content')
						<!-- ROW-1 OPEN -->
						<div class="row" id="user-profile">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-body">
										<div class="wideget-user">
											<div class="row">
												<div class="col-lg-6 col-md-12">
													<div class="wideget-user-desc d-sm-flex">
														<div class="">
															@if($user->hasProfilePhoto())
															{{-- <img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" width="180px"> --}}
															<span style="margin: 10px; width: 10rem; height: 10rem;" class="avatar avatar-xxl bradius cover-image" data-image-src= "{{ asset($user->getFullProfilePhoto()) }}"></span>
															@else
															<img class="" src="{{URL::asset('assets/images/users/10.jpg')}}" alt="img">
															@endif
														</div>
														<div class="user-wrap">
															@if($user->company)
                                                            <h3 style="font-weight: bold;">{{ $user->company->name }}</h3>
															@endif
															<h4>Persoană contact: {{ $user->getName() }}</h4>
															@if($user->company->company_type)
															<p class="mb-3">{{ $user->company->getCompanyTypeName() }}</strong></p>
															@endif
															<p class="text-muted mb-3">Înregistrat: <strong>{{ formatCarbonDate($user->created_at) }}</strong></p>

															{{-- <a href="#" class="btn btn-primary mt-1 mb-1"><i class="fa fa-envelope"></i> Contacteaza</a> --}}
														</div>
													</div>
												</div>
												@if($user->company)
                                                <div class="col-lg-3 col-md-12">
                                                    <div class="wideget-user-desc d-sm-flex">
														<div class="user-wrap mt-4">
                                                            <p>CUI: <strong>{{ $user->company->cui }}</strong></p>
                                                            <p>N.Î.: <strong>{{ $user->company->register_number }}</strong></p>
															@if($user->company->location)
                                                            <p><i class="fa fa-map-marker"></i> {{ $user->company->location->value }}</p>
															@endif
                                                            @if($user->company->phone)
                                                            <p style="font-size: 14px;font-weight: bold;"><a href="tel:{{ $user->company->phone }}" rel="nofollow"><i class="fa fa-phone"></i> {{ $user->company->phone }}</a></p>
                                                            @endif
                                                            @if($public_profile)
                                                            <p><a href="https://{{ $public_profile->website }}" rel="nofollow" target="_blank"><i class="fa fa-link"></i> {{ $public_profile->website }}</a></p>
                                                            @endif
														</div>
													</div>
                                                </div>
												@endif
												<div class="col-lg-3 col-md-12">
													<div class="wideget-user-info">
														
														<div class="wideget-user-icons">
                                                            @if($user->existsSocialProfile('facebook_profile') || $user->existsSocialProfile('youtube_profile') || $user->existsSocialProfile('instagram_profile') || $user->existsSocialProfile('twitter_profile'))
                                                            <p>Prezenți și pe:</p>
															@endif
															@if($user->existsSocialProfile('facebook_profile'))
															<a href="https://www.facebook.com/{{ $user->getSocialProfile('facebook_profile')->username }}" class="bg-facebook text-white" target="_blank">
																<i class="fa fa-facebook"></i></a>
															@endif

															@if($user->existsSocialProfile('youtube_profile'))
															<a href="https://www.youtube.com/channel/{{ $user->getSocialProfile('youtube_profile')->username }}" class="bg-danger text-white" target="_blank">
																<i class="fa fa-youtube"></i></a>
															@endif

															@if($user->existsSocialProfile('instagram_profile'))
															<a href="https://www.instagram.com/{{ $user->getSocialProfile('instagram_profile')->username }}" class="bg-danger text-white" target="_blank">
																<i class="fa fa-instagram"></i></a>
															@endif


															@if($user->existsSocialProfile('twitter_profile'))
															<a href="https://www.twitter.com/{{ $user->getSocialProfile('twitter_profile')->username }}" class="bg-info text-white" target="_blank">
																<i class="fa fa-twitter"></i></a> 
															@endif

															{{-- @if($user->existsSocialProfile('tiktok_profile'))
															<a href="https://www.tiktok.com/{{ $user->getSocialProfile('tiktok_profile')->username }}" class="bg-dark text-white" target="_blank">
																<i class="fab fa-tiktok"></i>T</a> 
															@endif --}}

															@if($user->badge)
																@if($user->badge->verified == true)
																<div class="d-flex direction-row mt-4">
																	<div><img src="{{ asset('assets/images/badges/firma-verificata.png') }}" alt="firma verificata" class="avatar cover-image" style="background: none!important;"></div>
																	<div><p class="text-center" style="font-size: 14px; padding: 6px;">Firmă verificată.</p></div>
																</div>
																@endif
															@endif
															
														</div>
													</div>

                                                    {{-- <div class="wideget-user-info">
                                                        <a href="#" class="btn btn-primary" ref="nofollow">Distribuie</a>
                                                    </div> --}}
												</div>
											</div>
										</div>
									</div>
									<div class="border-top">
										<div class="wideget-user-tab">
											<div class="tab-menu-heading">
												<div class="tabs-menu1">
													<ul class="nav">
														@if($user->user_name_profile)
														<li class=""><a href="/public/profil/profesionist/{{$user->user_name_profile->username}}" class="active show" ><i class="ti-angle-left"></i> Înapoi la profil</a></li>
														@else
														<li class=""><a href="/public/profil/profesionist/{{$user->username}}" class="active show" ><i class="ti-angle-left"></i> Înapoi la profil</a></li>
														@endif
														{{-- <li><a href="#tab-71" data-toggle="tab" class="">Portofoliu lucrari</a></li> --}}
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>


								<!-- GALLERY DEMO OPEN -->
						<div class="demo-gallery card">
							<div class="card-header">
								@if($single_project)
								<div class="card-title">
									{{ $single_project->title }} | #{{ $single_project->uuid }}
								</div>
								@endif
							</div>
							<div class="card-body">
								{{-- @if($single_project)
								<div class="row my-2">
									<div class="col-lg-12">
										{{ $single_project->description }}
									</div>
								</div>
								@endif --}}
								<ul id="lightgallery" class="list-unstyled row mt-4">
									@if($single_project->photos && $single_project->photos->count() > 0)
								
										@foreach($single_project->photos as $project_photo)
										<li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{URL::asset('storage/work_projects/' . $project_photo->name)}}" data-src="{{URL::asset('storage/work_projects/' . $project_photo->name)}}" data-sub-html="">
											<a href="">
												<img class="img-responsive" src="{{URL::asset('storage/work_projects/' . $project_photo->name)}}" alt="Thumb-1">
											</a>
										</li>
										@endforeach
									
									@endif

								</ul>
							</div>
						</div>
						<!-- GALLERY DEMO CLOSED -->
							</div><!-- COL-END -->
						</div>
						<!-- ROW-1 CLOSED -->
					</div>
				</div>
				<!-- CONTAINER CLOSED -->
			</div>
@endsection
@section('js')

<script src="{{ URL::asset('assets/plugins/gallery/picturefill.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lightgallery.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lightgallery-1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lg-pager.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lg-autoplay.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lg-fullscreen.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lg-zoom.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lg-hash.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/gallery/lg-share.js') }}"></script>


@endsection