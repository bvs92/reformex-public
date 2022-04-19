@extends('volgh.layouts.master')

@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('css')
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}
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
												<div class="text-center mb-4">
													<span><i class="fa fa-star text-warning"></i></span>
													<span><i class="fa fa-star text-warning"></i></span>
													<span><i class="fa fa-star text-warning"></i></span>
													<span><i class="fa fa-star-half-o text-warning"></i></span>
													<span><i class="fa fa-star-o text-warning"></i></span>
												</div>
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
								<div class="card">
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
								<!-- start card --><div class="card">
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


								<!-- start card --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Profile Sociale</h3>
									</div>
									<div class="card-body">

										<form method="POST" action="{{ route('user.social.profiles.save', auth()->user()->id) }}">
												@csrf
										
												<div class="form-row">
													<div class="col-lg-8">
														<label for="facebook_profile">Facebook</label>
														<input type="text" class="form-control @error('facebook_profile') is-invalid @enderror" id="facebook_profile" name="facebook_profile" placeholder="profilul.meu" value="{{ auth()->user()->getSocialProfile('facebook_profile')->username ?? old('facebook_profile') }}">
														@error('facebook_profile')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>

												<div class="form-row">
													<div class="col-lg-8">
														<label for="instagram_profile">Instagram</label>
														<input type="text" class="form-control @error('instagram_profile') is-invalid @enderror" id="instagram_profile" name="instagram_profile" placeholder="profilul_meu" value="{{ auth()->user()->getSocialProfile('instagram_profile')->username ?? old('instagram_profile') }}">
														@error('instagram_profile')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>

												<div class="form-row">
													<div class="col-lg-8">
														<label for="youtube_profile">Youtube</label>
														<input type="text" class="form-control @error('youtube_profile') is-invalid @enderror" id="youtube_profile" name="youtube_profile" placeholder="profilulmeu" value="{{ auth()->user()->getSocialProfile('youtube_profile')->username ?? old('youtube_profile') }}">
														@error('youtube_profile')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>

												<div class="form-row">
													<div class="col-lg-8">
														<label for="twitter_profile">Twitter</label>
														<input type="text" class="form-control @error('twitter_profile') is-invalid @enderror" id="twitter_profile" name="twitter_profile" placeholder="profilulmeu" value="{{ auth()->user()->getSocialProfile('twitter_profile')->username ?? old('twitter_profile') }}">
														@error('twitter_profile')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>

												<div class="form-row">
													<div class="col-lg-8">
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



								@if(auth()->user()->isPro())
								<!-- start card --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Editare informatii cereri personalizate</h3>
									</div>
									<div class="card-body">

										<form method="POST" action="{{ route('professionals.updatePro') }}">
											@csrf
											@method('PUT')

											<div class="row">
												<div class="col-lg-6 col-md-12">
													<div class="form-group">
														<label for="pro_name">Nume companie (de eliminat acest camp)</label>
														<input type="text" class="form-control @error('pro_name') is-invalid @enderror" id="pro_name" name="pro_name" placeholder="Nume firma" value="{{ auth()->user()->professional->getName() ?? old('pro_name') }}">
														@error('pro_name')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>
												<div class="col-lg-6 col-md-12">
													<div class="form-group">
														<label for="city">Oras</label>
														<input type="search" id="address-input" 
															class="form-control @error('city') has-error @enderror" 
															placeholder="Care este locatia (orasul) companiei?" 
															name="city" 
															value="{{ old('city') }}">
														@error('city')
																<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>
											</div>

											

											<input type="hidden" id="the-city" name="the-city" />
											<input type="hidden" id="administrative" name="administrative" />
											<input type="hidden" id="postal_code" name="postal_code" />
											<input type="hidden" id="lat" name="lat" />
											<input type="hidden" id="lng" name="lng" />

											<div class="form-row">
												<div class="col">
													<button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
												</div>
											</div>


										</form>

										<hr>
										<h4>Selectati categoriile de interes</h4>
										<form method="POST" action="{{ route('professionals.update.categories') }}">
											@csrf
											@method('PUT')
											<div class="form-group">
												<label for="categories">Categorii disponibile</label>
												@if($categories && $categories->count() > 0)
													<select name="categories[]" class="form-control @error('categories') has-error @enderror" id="categories" multiple>
													@foreach($categories as $category)
															<option 
															value="{{ $category->id }}"
															@if($my_categories->contains($category))
																	selected="selected"
															@endif
															>{{ $category->name }}</option>
													@endforeach
													</select>
												@endif
									
												@error('categories')
														<p class="small text-danger">{{ $message }}</p>
												@enderror
													
											</div>
									
											<div class="form-group">
												<button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
											</div>
										</form>

										<form action="{{ route('professionals.detach.categories') }}" method="POST">
											@csrf
											<button type="submit" class="btn btn-danger mb-2">Sterge categoriile atasate</button>
										</form>


									</div>
								</div><!-- end card -->

								<!-- start card company --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Informatii firma</h3>
									</div>
									<div class="card-body">
									<form action="{{ route('profile.company.save') }}" method="POST">
										@csrf
										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="company_name">Denumire firma</label>
													<input type="text" class="form-control @error('company_name') has-error @enderror" id="company_name" name="company_name" placeholder="Firma Mea SRL" value="{{ old('company_name') }}">
													@error('company_name')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="year_made">An infiintare</label>
													<input type="numeric" class="form-control @error('year_made') has-error @enderror" id="year_made" name="year_made" placeholder="2002" value="{{ old('year_made') }}">
													@error('year_made')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="phone">Numar de telefon</label>
													<input type="text" class="form-control @error('phone') has-error @enderror" id="phone" name="phone" placeholder="0740000000" value="{{ old('phone') }}">
													@error('phone')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="workers">Numar angajati</label>
													<input type="numeric" class="form-control @error('workers') has-error @enderror" id="workers" name="workers" placeholder="10" value="{{ old('workers') }}">
													@error('workers')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="cui">Cod Unic de Inregistrare (CUI)</label>
													<input type="numeric" class="form-control @error('cui') has-error @enderror" id="cui" name="cui" placeholder="12345678" value="{{ old('cui') }}">
													@error('cui')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="register_number">Numar Inmatriculare</label>
													<input type="text" class="form-control @error('register_number') has-error @enderror" id="register_number" name="register_number" placeholder="J28/123/2008" value="{{ old('register_number') }}">
													@error('register_number')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="administrative_company">Judet</label>
													<input type="text" class="form-control @error('administrative_company') has-error @enderror" id="administrative_company" name="administrative_company" placeholder="Olt" value="{{ old('administrative_company') }}">
													@error('administrative_company')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="city_company">Oras</label>
													<input type="text" class="form-control @error('city_company') has-error @enderror" id="city_company" name="city_company" placeholder="Corabia" value="{{ old('city_company') }}">
													@error('city_company')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="address_company">Adresa</label>
											<input type="text" class="form-control @error('address_company') has-error @enderror" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" value="{{ old('address_company') }}">
											@error('address_company')
													<p class="small text-danger">{{ $message }}</p>
											@enderror
										</div>

										<div class="form-group">
											<label class="form-label" for="bio">Descriere companie</label>
											<textarea name="bio" class="form-control @error('bio') has-error @enderror" rows="6" placeholder="Scurta descriere a companiei...">{{ old('bio') }}</textarea>
											@error('bio')
													<p class="small text-danger">{{ $message }}</p>
											@enderror
										</div>

										<div class="form-group">
											<label for="website" class="form-label">Site internet</label>
											<input type="text" class="form-control @error('website') has-error @enderror" name="website" placeholder="www.website.ro" value="{{ old('website') }}">
											@error('website')
													<p class="small text-danger">{{ $message }}</p>
											@enderror
										</div>

										
									</div>
									<div class="card-footer">
										<button type="submit" class="btn btn-success mt-1">Salveaza profil companie</button>
										<a href="#" class="btn btn-danger mt-1">Renunta</a>
									</div>
								</form>
								</div><!-- end card -->
								@endif {{-- end if PRO --}}

								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Recently Connected</h3>
									</div>
									<div class="card-body p-5">
										<div class="memberblock mb-0">
											<div class="row ">
												<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 pl-1 pr-1 m-t-5 m-b-5">
													<a href="" class="member"><img src="{{URL::asset('assets/images/users/13.jpg')}}" alt="">
														<div class="memmbername">Ajay Sriram</div>
													</a>
												</div>
												<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 pl-1 pr-1 m-t-5 m-b-5">
													<a href="" class="member"><img src="{{URL::asset('assets/images/users/8.jpg')}}" alt="">
														<div class="memmbername">Samantha</div>
													</a>
												</div>
												<div class="col-lg-2 col-md-3 col-sm-4 pl-1 pr-1 m-t-5 m-b-5">
													<a href="" class="member"><img src="{{URL::asset('assets/images/users/14.jpg')}}" alt="">
														<div class="memmbername">Stella</div>
													</a>
												</div>
												<div class="col-lg-2 col-md-3 col-sm-4 pl-1 pr-1 m-t-5 m-b-5">
													<a href="" class="member"><img src="{{URL::asset('assets/images/users/11.jpg')}}" alt="">
														<div class="memmbername">James Thomas</div>
													</a>
												</div>
												<div class="col-lg-2 col-md-3 col-sm-4 pl-1 pr-1 m-t-5 m-b-5">
													<a href="" class="member"><img src="{{URL::asset('assets/images/users/2.jpg')}}" alt="">
														<div class="memmbername">Christopher</div>
													</a>
												</div>
												<div class="col-lg-2 col-md-3 col-sm-4 pl-1 pr-1 m-t-5 m-b-5">
													<a href="" class="member"><img src="{{URL::asset('assets/images/users/15.jpg')}}" alt="">
														<div class="memmbername">Manish Sriram</div>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ROW-1 CLOSED -->

						<!-- ROW-2 OPEN -->
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header ">
										<h3 class="card-title ">Projects</h3>
										<div class="card-options">
											<button id="add__new__list" type="button" class="btn btn-md btn-primary " data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Add a new Project</button>
										</div>
									</div>
									<div class="table-responsive">
										<table class="table table-hover card-table table-striped table-vcenter table-outline text-nowrap">
											<thead>
												<tr>
													<th scope="col">ID</th>
													<th scope="col">Project Name</th>
													<th scope="col">Backend</th>
													<th scope="col">Deadline</th>
													<th scope="col">Team Members</th>
													<th scope="col">Edit Project Details </th>
													<th scope="col">list info</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>At vero eos et accusamus et iusto odio</td>
													<td>PHP</td>
													<td>15/11/2018</td>
													<td>15 Members</td>
													<td>
														<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
													</td>
													<td><a class="btn btn-sm btn-secondary" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
												</tr>
												<tr>
													<td>2</td>
													<td>voluptatum deleniti atque corrupti quos</td>
													<td>Angular js</td>
													<td>25/11/2018</td>
													<td>12 Members</td>
													<td>
														<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
													</td>
													<td><a class="btn btn-sm btn-secondary" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
												</tr>
												<tr>
													<td>3</td>
													<td>dignissimos ducimus qui blanditiis praesentium </td>
													<td>Java</td>
													<td>5/12/2018</td>
													<td>20 Members</td>
													<td>
														<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
													</td>
													<td><a class="btn btn-sm btn-secondary" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
												</tr>
												<tr>
													<td>4</td>
													<td>deleniti atque corrupti quos dolores  </td>
													<td>Phython</td>
													<td>14/12/2018</td>
													<td>10 Members</td>
													<td>
														<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
													</td>
													<td><a class="btn btn-sm btn-secondary" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
												</tr>
												<tr>
													<td>5</td>
													<td>et quas molestias excepturi sint occaecati</td>
													<td>Phython</td>
													<td>4/12/2018</td>
													<td>17 Members</td>
													<td>
														<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
													</td>
													<td><a class="btn btn-sm btn-secondary" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!-- ROW-2 CLOSED -->

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

@endsection