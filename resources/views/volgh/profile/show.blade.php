@extends('volgh.layouts.master')



@section('css')
@endsection
@section('page-header')
						<!-- PAGE-HEADER -->
							<div>
								<h1 class="page-title">Profil</h1>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="/index">Acasa</a></li>
									<li class="breadcrumb-item active" aria-current="page">Profil</li>
								</ol>
							</div>							
						<!-- PAGE-HEADER END -->
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
														<div class="wideget-user-img">
															@if(auth()->user()->hasProfilePhoto())
															<img src="{{ asset(auth()->user()->getFullProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" width="180px">
															@else
															<img class="" src="{{URL::asset('assets/images/users/10.jpg')}}" alt="img">
															@endif
														</div>
														<div class="user-wrap">
															<h4>{{ auth()->user()->getName() }}</h4>
															<h6 class="text-muted mb-3">Inregistrare: {{ formatShortCarbonDate(auth()->user()->created_at) }} ({{ carbonDateToRo(auth()->user()->created_at) }})</h6>
															{{-- <a href="#" class="btn btn-primary mt-1 mb-1"><i class="fa fa-rss"></i> Follow</a>
															<a href="#" class="btn btn-secondary mt-1 mb-1"><i class="fa fa-envelope"></i> E-mail</a> --}}
															<a href="#" class="btn btn-info btn-sm mt-1 mb-1"><i class="fa fa-eye"></i> Profil public</a>
															<a href="{{ route('user.profile.edit.personal') }}" class="btn btn-warning btn-sm mt-1 mb-1"><i class="fa fa-edit"></i> Editare</a>
														</div>
													</div>
												</div>
												<div class="col-lg-6 col-md-12">
													<div class="wideget-user-info">
														<div class="wideget-user-warap">
															<div class="wideget-user-warap-l">
																<h5>Social</h5>
																{{-- <p>Cereri lansate</p> --}}

																<div class="wideget-user-icons">
																	@if(auth()->user()->existsSocialProfile('facebook_profile'))
																	<a href="https://www.facebook.com/{{ auth()->user()->getSocialProfile('facebook_profile')->username }}" class="bg-facebook text-white" target="_blank">
																		<i class="fa fa-facebook"></i></a>
																	@endif

																	@if(auth()->user()->existsSocialProfile('youtube_profile'))
																	<a href="https://www.youtube.com/channel/{{ auth()->user()->getSocialProfile('youtube_profile')->username }}" class="bg-danger text-white" target="_blank">
																		<i class="fa fa-youtube"></i></a>
																	@endif

																	@if(auth()->user()->existsSocialProfile('instagram_profile'))
																	<a href="https://www.instagram.com/{{ auth()->user()->getSocialProfile('instagram_profile')->username }}" class="bg-danger text-white" target="_blank">
																		<i class="fa fa-instagram"></i></a>
																	@endif


																	@if(auth()->user()->existsSocialProfile('twitter_profile'))
																	<a href="https://www.twitter.com/{{ auth()->user()->getSocialProfile('twitter_profile')->username }}" class="bg-info text-white" target="_blank">
																		<i class="fa fa-twitter"></i></a> 
																	@endif

																	@if(auth()->user()->existsSocialProfile('tiktok_profile'))
																	<a href="https://www.tiktok.com/{{ auth()->user()->getSocialProfile('tiktok_profile')->username }}" class="bg-dark text-white" target="_blank">
																		<i class="fab fa-tiktok"></i>T</a> 
																	@endif
																	
																</div>

															</div>
															<div class="wideget-user-warap-r">
																<h5>Nota</h5>
																{{-- <p>Cereri tratate</p> --}}

																@if(auth()->user()->isPro())
																	@if(auth()->user()->professional->reviews && auth()->user()->professional->reviews->count() > 0)
																		<div class="wideget-user-rating">
																			@for($i = 1; $i <= 5; $i++)
																				@if($i <= auth()->user()->professional->getRating())
																					<a href="#"><i class="fa fa-star text-warning"></i></a>
																				@else
																					<a href="#"><i class="fa fa-star-o text-warning mr-1"></i></a> 
																				@endif
																			@endfor
																			<span>{{ auth()->user()->professional->getRating() }} din 5 stele ({{ auth()->user()->professional->reviews->count() }} @if(auth()->user()->professional->reviews->count() == 1) Recenzie @else Recenzii @endif)</span>
																		</div>
																	@endif
																@endif

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="border-top">
										<div class="wideget-user-tab">
											<div class="tab-menu-heading">
												<div class="tabs-menu1">
													<ul class="nav">
														<li class=""><a href="#tab-51" class="active show" data-toggle="tab">Informatii personale</a></li>
														<li><a href="#tab-61" data-toggle="tab" class="">Modul profesionist</a></li>
														<li><a href="#tab-71" data-toggle="tab" class="">Proiecte executate</a></li>
														<li><a href="#tab-81" data-toggle="tab" class="">Recenzii</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-body">
										<div class="border-0">
											<div class="tab-content">
												<div class="tab-pane active show" id="tab-51">
													<div id="profile-log-switch">
														<div class="media-heading">
															<h5><strong>Personal Information</strong></h5>
														</div>
														<div class="table-responsive ">
															<table class="table row table-borderless">
																<tbody class="col-lg-12 col-xl-6 p-0">
																	<tr>
																		<td><strong>Nume complet :</strong> {{ auth()->user()->getName() }}</td>
																		@if(auth()->user()->hasUsername())
																		<td><strong>Username :</strong>@ {{ auth()->user()->getUsername() }}</td>
																		@endif
																	</tr>
																	@if(auth()->user()->isPro())
																	<tr>
																		<td><strong>Locatie :</strong> {{ auth()->user()->professional->getLocation() }}</td>
																	</tr>
																	@endif
																	<tr>
																		<td><strong>Languages :</strong> English, German, Spanish.</td>
																	</tr>
																</tbody>
																<tbody class="col-lg-12 col-xl-6 p-0">
																	<tr>
																		<td><strong>Site internet :</strong> www.site.ro</td>
																	</tr>
																	<tr>
																		<td><strong>Email :</strong> {{ auth()->user()->email }}</td>
																	</tr>
																	@if(auth()->user()->isPro())
																	<tr>
																		<td><strong>Numar telefon :</strong> {{ auth()->user()->professional->phone }} </td>
																	</tr>
																	@endif
																</tbody>
															</table>
														</div>
														<div class="row profie-img">
															<div class="col-md-12">
																<div class="media-heading">
																	<h5><strong>Biography</strong></h5>
																</div>
																<p>
																	Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus</p>
																<p class="mb-0">because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter but because those who do not know how to pursue consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure.</p>
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane" id="tab-61">
													<ul class="widget-users row">
														<li class="col-lg-4  col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/15.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-3">James Thomas</h4>
																	<p class="card-text">Web designer</p>
																</div>
																<div class="card-footer text-center">
																	<div class="row user-social-detail">
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-lg-4 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/9.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-3">George Clooney</h4>
																	<p class="card-text">Web designer</p>
																</div>
																<div class="card-footer text-center">
																	<div class="row user-social-detail">
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-lg-4 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/20.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-3">Robert Downey Jr.</h4>
																	<p class="card-text">Web designer</p>
																</div>
																<div class="card-footer text-center">
																	<div class="row user-social-detail">
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-lg-4 col-md-6 col-sm-12 col-12">
															<div class="card mb-lg-0">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/12.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-3">Emma Watson</h4>
																	<p class="card-text">Web designer</p>
																</div>
																<div class="card-footer text-center">
																	<div class="row user-social-detail">
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-lg-4 col-md-6 col-sm-12 col-12">
															<div class="card mb-lg-0">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/4.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-3">Mila Kunis</h4>
																	<p class="card-text">Web designer</p>
																</div>
																<div class="card-footer text-center">
																	<div class="row user-social-detail">
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-lg-4 col-md-6 col-sm-12 col-12">
															<div class="card mb-0">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/6.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-3">Ryan Gossling</h4>
																	<p class="card-text">Web designer</p>
																</div>
																<div class="card-footer text-center">
																	<div class="row user-social-detail">
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-lg-4 col-sm-4 col-4">
																			<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
													</ul>
												</div>
												<div class="tab-pane" id="tab-71">
													<div class="row">
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5" src="{{URL::asset('assets/images/media/8.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5" src="{{URL::asset('assets/images/media/10.jpg')}}" alt="banner image ">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5" src="{{URL::asset('assets/images/media/11.jpg')}}" alt="banner image ">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5 " src="{{URL::asset('assets/images/media/12.jpg')}}" alt="banner image ">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5" src="{{URL::asset('assets/images/media/13.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5" src="{{URL::asset('assets/images/media/14.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-5" src="{{URL::asset('assets/images/media/15.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-0" src="{{URL::asset('assets/images/media/16.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-0" src="{{URL::asset('assets/images/media/17.jpg')}}" alt="banner image">
														</div><div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-0" src="{{URL::asset('assets/images/media/18.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded mb-0" src="{{URL::asset('assets/images/media/19.jpg')}}" alt="banner image">
														</div>
														<div class="col-lg-3 col-md-6">
															<img class="img-fluid rounded" src="{{URL::asset('assets/images/media/20.jpg')}}" alt="banner image">
														</div>
													</div>
												</div>

												<div class="tab-pane" id="tab-81">
													<h2>Recenzii</h2>
													@if(auth()->user()->isPro())
														@if(auth()->user()->professional->reviews && auth()->user()->professional->reviews->count() > 0)
														<ul class="widget-users row">
															@foreach(auth()->user()->professional->reviews as $review)
															<li class="col-lg-6  col-md-6 col-sm-12 col-12">
																<div class="card">
																	<div class="card-body text-center">
																		{{-- <span class="avatar avatar-xxl brround cover-image" data-image-src="{{URL::asset('assets/images/users/15.jpg')}}"></span> --}}
																		@if($review->user)
																			<h4 class="h4 mb-0 mt-3">{{ strtoupper($review->user->last_name[0]) . ". " . $review->user->first_name }}</h4>
																		@else
																			@if($review->name)
																				<h4 class="h4 mb-0 mt-3">{{ $review->getName() }}</h4>
																			@else
																				<h4 class="h4 mb-0 mt-3">Nume indisponibil</h4>
																			@endif
																		@endif
																		<p class="card-text">
																			@for($i = 1; $i <= 5; $i++)
																				@if($i <= $review->rating)
																					<a href="#"><i class="fa fa-star text-warning"></i></a>
																				@else
																					<a href="#"><i class="fa fa-star-o text-warning mr-1"></i></a> 
																				@endif
																			@endfor
																			<span>{{ $review->rating }} din 5 stele </span>
																		</p>
																		<p class="card-text">{{ $review->message }}</p>
																	</div>
																	{{-- <div class="card-footer text-center">
																		<div class="row user-social-detail">
																			<div class="col-lg-4 col-sm-4 col-4">
																				<a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
																			</div>
																			<div class="col-lg-4 col-sm-4 col-4">
																				<a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
																			</div>
																			<div class="col-lg-4 col-sm-4 col-4">
																				<a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																			</div>
																		</div>
																	</div> --}}
																</div>
															</li>
															@endforeach
														</ul>
														@endif
													@else
													<p class="text-center">Nu exista recenzii.</p>
													@endif
												</div>
												
											</div>
										</div>
									</div>
								</div>
							</div><!-- COL-END -->
						</div>
						<!-- ROW-1 CLOSED -->
					</div>
				</div>
				<!-- CONTAINER CLOSED -->
			</div>
@endsection
@section('js')
@endsection