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
		<h1 class="page-title">Editare Profil</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Acasa</a></li>
			<li class="breadcrumb-item active" aria-current="page">Editare Profil</li>
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
												<h3 class="username text-dark mb-2">{{ $user->getName() }}</h3>
												@if(auth()->user()->isPro())
													<p class="mb-1 text-muted">Administrator | {{ $user->professional->getLocation() }}</p>
												@endif

												@if(auth()->user()->isPro())
													@if(auth()->user()->professional->reviews && auth()->user()->professional->reviews->count() > 0)
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
													@endif
												@endif


												<div class="socials text-center mt-3">
													@if($user->existsSocialProfile('facebook_profile'))
													<a href="https://www.facebook.com/{{ $user->getSocialProfile('facebook_profile')->username }}" class="btn btn-circle btn-primary" target="_blank">
														<i class="fa fa-facebook"></i></a>
													@endif

													@if($user->existsSocialProfile('youtube_profile'))
													<a href="https://www.youtube.com/channel/{{ $user->getSocialProfile('youtube_profile')->username }}" class="btn btn-circle btn-danger" target="_blank">
														<i class="fa fa-youtube"></i></a>
													@endif

													@if($user->existsSocialProfile('instagram_profile'))
													<a href="https://www.instagram.com/{{ $user->getSocialProfile('instagram_profile')->username }}" class="btn btn-circle btn-danger" target="_blank">
														<i class="fa fa-instagram"></i></a>
													@endif


													@if($user->existsSocialProfile('twitter_profile'))
													<a href="https://www.twitter.com/{{ $user->getSocialProfile('twitter_profile')->username }}" class="btn btn-circle btn-info " target="_blank">
														<i class="fa fa-twitter"></i></a> 
													@endif

													@if($user->existsSocialProfile('tiktok_profile'))
													<a href="https://www.tiktok.com/{{ $user->getSocialProfile('tiktok_profile')->username }}" class="btn btn-circle btn-black " target="_blank">
														<i class="fab fa-tiktok"></i>T</a> 
													@endif

												</div>
											</div>
										</div>
									</div>
								</div>


								<div class="card panel-theme">
									<div class="card-header">
										<div class="float-left">
											<h3 class="card-title">Contact</h3>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="card-body no-padding">
										<ul class="list-group no-margin">
										<li class="list-group-item"><i class="fa fa-envelope mr-4"></i> {{ $user->email }}</li>
										@if(auth()->user()->isPro())
												<li class="list-group-item"><i class="fa fa-globe mr-4"></i> www.site.com</li>
												<li class="list-group-item"><i class="fa fa-phone mr-4"></i> +125 5826 3658 </li>
												<li class="list-group-item"><i class="fa fa-map-marker mr-4"></i> {{ $user->professional->getLocation() }} </li>
											@endif
										</ul>
									</div>
								</div>
							</div>






							<div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">


								<!-- start tabs --><div class="panel panel-primary bg-white">
									<div class="tab-menu-heading">
										<div class="tabs-menu">
											<!-- Tabs -->
											<ul class="nav panel-tabs">
												<li ><a href="#tab1" class="active" data-toggle="tab">Informatii personale</a></li>
												<li ><a href="#tab1-1" data-toggle="tab">Parola</a></li>
												<li><a href="#tab2" data-toggle="tab">Profile sociale</a></li>
											</ul>
										</div>
									</div>
									<div class="panel-body tabs-menu-body">
										<div class="tab-content">
											<div class="tab-pane active" id="tab1">
												<!-- start card --><div class="">
													<div class="card-header">
														<h3 class="card-title">Editare informatii personale</h3>
													</div>
													<div class="card-body">

														<form method="POST" action="{{ route('user.profile.update') }}">
																@csrf
																@method('PUT')
														
																<div class="form-row">
																	<div class="col">
																		<input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Popescu" value="{{ auth()->user()->first_name ?? old('first_name') }}">
																		@error('first_name')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																	<div class="col">
																		<input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Andrei" value="{{ auth()->user()->last_name ?? old('last_name') }}">
																		@error('last_name')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>
																<br>
																<div class="form-row">
																	<div class="col">
																		<input type="text" disabled="disabled" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nume@email.com" value="{{ auth()->user()->email ?? old('email') }}">
																		@error('email')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																	<div class="col">
																		<button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
																	</div>
																</div>
														</form>
				
														<hr>
														<!-- start profile photo --><div class="row">
															<div class="col-lg-6">
																
																<div class="text-center">
																	<div class="userprofile">
																		<div class="userpic-normal"> 
																			@if(auth()->user()->hasProfilePhoto())
																				<img src="{{ asset(auth()->user()->getFullProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="img-thumbnail">
																			@else
																				<img src="{{URL::asset('assets/images/users/10.jpg')}}" > 
																			@endif
																		</div>
																		<div class="text-center my-2">
																			@if(auth()->user()->hasProfilePhoto())
																				<a class="btn btn-danger btn-sm mt-1 mb-1 text-light" onclick="event.preventDefault();document.getElementById('deleteProfilePhoto').submit();"><i class="fe fe-camera-off mr-1"></i> Elimina avatar</a>
																				<form action="{{ route('profile.photo.delete', auth()->user()->profile->id) }}" method="POST" id="deleteProfilePhoto" style="display: none;">
																					@csrf
																					@method('DELETE')
																				</form>
																			@endif
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-lg-6">
																<form method="POST" action="{{ route('user.profile.update.photo') }}" enctype="multipart/form-data">
																	@csrf
																	@method('PUT')
				
																	<div class="form-group">
																		<div class="form-label">Seteaza o poza de profil</div>
																		<div class="custom-file">
																			<input type="file" name="profile_photo" class="custom-file-input @error('profile_photo') is-invalid @enderror" id="profile_photo" >
																			<label class="custom-file-label">Alege poza profil</label>
																			@error('profile_photo')
																				<p class="small text-danger">{{ $message }}</p>
																			@enderror
																		</div>
																	</div>
																	<div class="form-group">
																		<button type="submit" class="btn btn-success float-right">Salveaza poza profil</button>
																	</div>
																</form>
															</div>
														</div><!-- end profile photo -->
														
				
													</div>
												</div><!-- end card -->
											</div>
											<div class="tab-pane" id="tab1-1">
												<div class="">
													<div class="card-header">
														<div class="card-title">Schimba Parola</div>
													</div>
													<div class="card-body">
														<form method="POST" action="{{ route('user.password.change') }}">
																@csrf
																@method('PUT')
																<div class="form-group">
																	<label for="password">Parola curenta</label>
																	<input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
																	@error('password')
																	<p class="small text-danger">{{ $message }}</p>
																	@enderror
																</div>
				
																<div class="form-group">
																	<label for="new_password">Noua parola</label>
																	<input type="text" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
																	@error('new_password')
																	<p class="small text-danger">{{ $message }}</p>
																	@enderror
																</div>
																<div class="form-group">
																	<label for="new_password_confirmation">Confirma noua parola</label>
																	<input type="text" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation">
																	@error('new_password_confirmation')
																	<p class="small text-danger">{{ $message }}</p>
																	@enderror
																</div>
				
																<div class="card-footer text-right">
																	<button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
																</div>	
														</form>
													</div>
				
													<div class="card-body mt-4">
														<h3>Modifica parola (pentru Admin)</h3>
														<form method="POST" action="{{ route('users.admin.ChangePassword', $user) }}">
																@csrf
																@method('PUT')
												
																<div class="row">
																	<div class="col-lg-12">
																		<label for="password">Noua Parola</label>
																		<input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
																		@error('password')
																			<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
												
																	<div class="col-lg-12">
																		<div class="align-bottom">
																			<button type="submit" class="btn btn-primary my-2">Modifica parola</button>
																		</div>
																	</div>
																</div>
															  
														</form>
												
													</div>
													
												</div><!-- end card -->
											</div>
											<div class="tab-pane" id="tab2">
												<!-- start card --><div class="">
													<div class="card-header">
														<h3 class="card-title">Profile Sociale</h3>
													</div>
													<div class="card-body">

														<form method="POST" action="{{ route('user.social.profiles.save', auth()->user()->id) }}">
																@csrf


														
																<div class="form-row">
																	<div class="col-lg-12">
																		<label for="facebook_profile">Facebook</label>
																		<input type="text" class="form-control @error('facebook_profile') is-invalid @enderror" id="facebook_profile" name="facebook_profile" placeholder="profilul.meu" value="{{ auth()->user()->getSocialProfile('facebook_profile')->username ?? old('facebook_profile') }}">
																		@error('facebook_profile')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>

																<div class="form-row">
																	<div class="col-lg-12">
																		<label for="instagram_profile">Instagram</label>
																		<input type="text" class="form-control @error('instagram_profile') is-invalid @enderror" id="instagram_profile" name="instagram_profile" placeholder="profilul_meu" value="{{ auth()->user()->getSocialProfile('instagram_profile')->username ?? old('instagram_profile') }}">
																		@error('instagram_profile')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>

																<div class="form-row">
																	<div class="col-lg-12">
																		<label for="youtube_profile">Youtube</label>
																		<input type="text" class="form-control @error('youtube_profile') is-invalid @enderror" id="youtube_profile" name="youtube_profile" placeholder="profilulmeu" value="{{ auth()->user()->getSocialProfile('youtube_profile')->username ?? old('youtube_profile') }}">
																		@error('youtube_profile')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>

																<div class="form-row">
																	<div class="col-lg-12">
																		<label for="twitter_profile">Twitter</label>
																		<input type="text" class="form-control @error('twitter_profile') is-invalid @enderror" id="twitter_profile" name="twitter_profile" placeholder="profilulmeu" value="{{ auth()->user()->getSocialProfile('twitter_profile')->username ?? old('twitter_profile') }}">
																		@error('twitter_profile')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>

																<div class="form-row">
																	<div class="col-lg-12">
																		<label for="tiktok_profile">Tik Tok</label>
																		<input type="text" class="form-control @error('tiktok_profile') is-invalid @enderror" id="tiktok_profile" name="tiktok_profile" placeholder="profilulmeu" value="{{ auth()->user()->getSocialProfile('tiktok_profile')->username ?? old('tiktok_profile') }}">
																		@error('tiktok_profile')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>


																<br>
																<div class="form-row">
																	<div class="col">
																		<button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
																	</div>
																</div>
														</form>

													</div>
												</div><!-- end card Profil SOcial -->
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

<script>

    // De preluat mai multe informatii din algolia? Cod postal, lat, long? Tabel separat doar pentru aceste informatii?
    // var placesAutocomplete = places({
    //   appId: '{{ config('services.algolia.appId') }}',
    //   apiKey: '{{ config('services.algolia.apiKey') }}',
    //   container: document.querySelector('#address-input')
      
    // });

    const fixedOptions = {
        appId: '{{ config('services.algolia.appId') }}',
        apiKey: '{{ config('services.algolia.apiKey') }}',
        container: document.querySelector('#address-input')
    };

    const reconfigurableOptions = {
        language: 'ro', 
        countries: ['ro'], 
        type: 'city', 
        aroundLatLngViaIP: false 
    };
    const placesInstance = places(fixedOptions).configure(reconfigurableOptions);

    placesInstance.on('change', function(e){
        console.log(e.suggestion);
        document.getElementById('the-city').value = e.suggestion.name;
        document.getElementById('administrative').value = e.suggestion.administrative;
        document.getElementById('postal_code').value = e.suggestion.postcode;
        document.getElementById('lat').value = e.suggestion.latlng.lat;
        document.getElementById('lng').value = e.suggestion.latlng.lng;

        
    })

    
  </script>


<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>

@endsection