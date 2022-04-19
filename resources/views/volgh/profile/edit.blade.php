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
													@if($user->hasProfilePhoto())
														<img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" class="userpicimg">
													@else
														<img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="" class="userpicimg"> 
													@endif
												</div>
												<h3 class="username text-dark mb-2">{{ $user->getName() }}</h3>
												<p class="mb-1 text-muted">@if($user->hasRoles()) {{ ucfirst($user->getFirstRole()->name) }} @endif @if($user->isPro()) | {{ $user->professional->getLocation() }} @endif</p>
												<div class="text-center mb-4">
													<span><i class="fa fa-star text-warning"></i></span>
													<span><i class="fa fa-star text-warning"></i></span>
													<span><i class="fa fa-star text-warning"></i></span>
													<span><i class="fa fa-star-half-o text-warning"></i></span>
													<span><i class="fa fa-star-o text-warning"></i></span>
												</div>
												<div class="socials text-center mt-3">
													<a href="" class="btn btn-circle btn-primary ">
													<i class="fa fa-facebook"></i></a> <a href="" class="btn btn-circle btn-danger ">
													<i class="fa fa-google-plus"></i></a> <a href="" class="btn btn-circle btn-info ">
													<i class="fa fa-twitter"></i></a> <a href="" class="btn btn-circle btn-warning "><i class="fa fa-envelope"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header">
										<div class="card-title">Schimba Parola (Pentru admin)</div>
									</div>
									<div class="card-body mt-4">
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
										@if($user->isPro())
												<li class="list-group-item"><i class="fa fa-globe mr-4"></i> www.site.com</li>
												<li class="list-group-item"><i class="fa fa-phone mr-4"></i> +125 5826 3658 </li>
												<li class="list-group-item"><i class="fa fa-map-marker mr-4"></i> {{ $user->professional->getLocation() }} </li>
											@endif
										</ul>
									</div>
								</div>
							</div>






							<div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

								<!-- start tabs --><div class="panel panel-primary">
									<div class="tab-menu-heading">
										<div class="tabs-menu">
											<!-- Tabs -->
											<ul class="nav panel-tabs">
												<li ><a href="#tab1" class="active" data-toggle="tab">Informatii personale</a></li>
												<li><a href="#tab2" data-toggle="tab">Tab 2</a></li>
												<li><a href="#tab3" data-toggle="tab">Tab 3</a></li>
												<li><a href="#tab4" data-toggle="tab">Tab 4</a></li>
											</ul>
										</div>
									</div>
									<div class="panel-body tabs-menu-body">
										<div class="tab-content">
											<div class="tab-pane active" id="tab1">
																<!-- start card --><div class="card">
													<div class="card-header">
														<h3 class="card-title">Editare informatii personale</h3>
													</div>
													<div class="card-body">

														<form method="POST" action="{{ route('users.admin.updateProfile', $user) }}">
																@csrf
																@method('PUT')
														
																<div class="form-row">
																	<div class="col">
																		<input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Popescu" value="{{ $user->first_name ?? old('first_name') }}">
																		@error('first_name')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																	<div class="col">
																		<input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Andrei" value="{{ $user->last_name ?? old('last_name') }}">
																		@error('last_name')
																		<p class="small text-danger">{{ $message }}</p>
																		@enderror
																	</div>
																</div>
																<br>
																<div class="form-row">
																	<div class="col">
																		<input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nume@email.com" value="{{ $user->email ?? old('email') }}">
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
																			@if($user->hasProfilePhoto())
																				<img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" class="img-thumbnail">
																			@else
																				<p class="text-small text-center text-muted">Nu exista poza de profil.</p>
																			@endif
																		</div>
																		<div class="text-center my-2">
																			@if($user->hasProfilePhoto())
																				<a class="btn btn-danger btn-sm mt-1 mb-1 text-light" onclick="event.preventDefault();document.getElementById('deleteProfilePhoto').submit();"><i class="fe fe-camera-off mr-1"></i> Elimina avatar</a>
																				<form action="{{ route('users.admin.photo.delete', $user->profile->id) }}" method="POST" id="deleteProfilePhoto" style="display: none;">
																					@csrf
																					@method('DELETE')
																				</form>
																			@endif
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-lg-6">
																<form method="POST" action="{{ route('users.admin.update.photo', $user) }}" enctype="multipart/form-data">
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


														<div class="row">
															<hr>
															<div class="col-lg-6">
																<p>Status utilizator: 
																	@if($user->getStatus() == '1') 
																		<span class="badge badge-success">Activ</span>
																	@else
																		<span class="badge badge-danger">Inactiv</span>
																	@endif
																</p>
															</div>
															<div class="col-lg-6">
																<form action="{{ route('users.admin.change.status', $user->id) }}" method="POST">
																	@csrf
																	@method('PUT')
																	@if($user->getStatus() == '1') 
																		<button class="btn btn-danger btn-sm">Marcare ca inactiv</button>
																	@else
																		<button class="btn btn-success btn-sm">Marcare ca activ</button>
																	@endif
																</form>
															</div>
														</div>
														

													</div>
												</div><!-- end card -->
											</div>
											<div class="tab-pane  " id="tab2">
												<p> default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like</p>
												<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et</p>
											</div>
											<div class="tab-pane " id="tab3">
												<p>over the years, sometimes by accident, sometimes on purpose (injected humour and the like</p>
												<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et</p>
											</div>
											<div class="tab-pane  " id="tab4">
												<p>page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like</p>
												<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et</p>
											</div>
										</div>
									</div>
								</div><!-- end panel -->

								<!-- start card --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Editare informatii personale</h3>
									</div>
									<div class="card-body">

										<form method="POST" action="{{ route('users.admin.updateProfile', $user) }}">
												@csrf
												@method('PUT')
										
												<div class="form-row">
													<div class="col">
														<input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Popescu" value="{{ $user->first_name ?? old('first_name') }}">
														@error('first_name')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
													<div class="col">
														<input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Andrei" value="{{ $user->last_name ?? old('last_name') }}">
														@error('last_name')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>
												<br>
												<div class="form-row">
													<div class="col">
														<input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nume@email.com" value="{{ $user->email ?? old('email') }}">
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
															@if($user->hasProfilePhoto())
																<img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" class="img-thumbnail">
															@else
																<p class="text-small text-center text-muted">Nu exista poza de profil.</p>
															@endif
														</div>
														<div class="text-center my-2">
															@if($user->hasProfilePhoto())
																<a class="btn btn-danger btn-sm mt-1 mb-1 text-light" onclick="event.preventDefault();document.getElementById('deleteProfilePhoto').submit();"><i class="fe fe-camera-off mr-1"></i> Elimina avatar</a>
																<form action="{{ route('users.admin.photo.delete', $user->profile->id) }}" method="POST" id="deleteProfilePhoto" style="display: none;">
																	@csrf
																	@method('DELETE')
																</form>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<form method="POST" action="{{ route('users.admin.update.photo', $user) }}" enctype="multipart/form-data">
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


										<div class="row">
											<hr>
											<div class="col-lg-6">
												<p>Status utilizator: 
													@if($user->getStatus() == '1') 
														<span class="badge badge-success">Activ</span>
													@else
														<span class="badge badge-danger">Inactiv</span>
													@endif
												</p>
											</div>
											<div class="col-lg-6">
												<form action="{{ route('users.admin.change.status', $user->id) }}" method="POST">
													@csrf
													@method('PUT')
													@if($user->getStatus() == '1') 
														<button class="btn btn-danger btn-sm">Marcare ca inactiv</button>
													@else
														<button class="btn btn-success btn-sm">Marcare ca activ</button>
													@endif
												</form>
											</div>
										</div>
										

									</div>
								</div><!-- end card -->





								@if($user->isPro())
								<!-- start card --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Editare informatii companie</h3>
									</div>
									<div class="card-body">

										<form method="POST" action="{{ route('professionals.updatePro') }}">
											@csrf
											@method('PUT')

											<div class="row">
												<div class="col-lg-6 col-md-12">
													<div class="form-group">
														<label for="pro_name">Nume companie</label>
														<input type="text" class="form-control @error('pro_name') is-invalid @enderror" id="pro_name" name="pro_name" placeholder="Nume firma" value="{{ $user->professional->getName() ?? old('pro_name') }}">
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

								<!-- start card --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Categorii de interes</h3>
									</div>
									<div class="card-body">
						
										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="exampleInputname">First Name</label>
													<input type="text" class="form-control" id="exampleInputname" placeholder="First Name">
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="exampleInputname1">Last Name</label>
													<input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Last Name">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="exampleInputEmail1">Email address</label>
											<input type="email" class="form-control" id="exampleInputEmail1" placeholder="email address">
										</div>
										<div class="form-group">
											<label for="exampleInputnumber">Conatct Number</label>
											<input type="number" class="form-control" id="exampleInputnumber" placeholder="ph number">
										</div>
										<div class="form-group">
											<label class="form-label">About Me</label>
											<textarea class="form-control" rows="6">My bio.........</textarea>
										</div>
										<div class="form-group">
											<label class="form-label">Website</label>
											<input class="form-control" placeholder="http://splink.com">
										</div>
										<div class="form-group">
											<label class="form-label">Data infiintare companie</label>
											<div class="row">
												<div class="col-md-4">
													<select class="form-control">
														<option value="0">Date</option>
														<option value="1">01</option>
														<option value="2">02</option>
														<option value="3">03</option>
														<option value="4">04</option>
														<option value="5">05</option>
														<option value="6">06</option>
														<option value="7">07</option>
														<option value="8">08</option>
														<option value="9">09</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>
														<option value="16">16</option>
														<option value="17">17</option>
														<option value="18">18</option>
														<option value="19">19</option>
														<option value="20">20</option>
														<option value="21">21</option>
														<option value="22">22</option>
														<option value="23">23</option>
														<option value="24">24</option>
														<option value="25">25</option>
														<option value="26">26</option>
														<option value="27">27</option>
														<option value="28">28</option>
														<option value="29">29</option>
														<option value="30">30</option>
														<option value="31">31</option>
													</select>
												</div>
												<div class="col-md-4">
													<select class="form-control">
														<option value="0">Mon</option>
														<option value="1">Jan</option>
														<option value="2">Feb</option>
														<option value="3">Mar</option>
														<option value="4">Apr</option>
														<option value="5">May</option>
														<option value="6">June</option>
														<option value="7">July</option>
														<option value="8">Aug</option>
														<option value="9">Sep</option>
														<option value="10">Oct</option>
														<option value="11">Nov</option>
														<option value="12">Dec</option>
													</select>
												</div>
												<div class="col-md-4">
													<select class="form-control">
														<option value="0">Year</option>
														<option value="1">2018</option>
														<option value="2">2017</option>
														<option value="3">2016</option>
														<option value="4">2015</option>
														<option value="5">2014</option>
														<option value="6">2013</option>
														<option value="7">2102</option>
														<option value="8">2012</option>
														<option value="9">2011</option>
														<option value="10">2010</option>
														<option value="11">2009</option>
														<option value="12">2008</option>
														<option value="13">2007</option>
														<option value="14">2006</option>
														<option value="15">2005</option>
														<option value="16">2004</option>
														<option value="17">2003</option>
														<option value="18">2002</option>
														<option value="19">2001</option>
														<option value="20">1999</option>
														<option value="21">1998</option>
														<option value="22">1997</option>
														<option value="23">1996</option>
														<option value="24">1995</option>
														<option value="25">1994</option>
														<option value="26">1993</option>
														<option value="27">1992</option>
														<option value="28">1991</option>
														<option value="29">1990</option>
														<option value="30">1989</option>
														<option value="31">1988</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<a href="#" class="btn btn-success mt-1">Save</a>
										<a href="#" class="btn btn-danger mt-1">Cancel</a>
									</div>
								</div><!-- end card -->
								@endif {{-- end if PRO --}}


								<!-- start card company --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Informatii firma</h3>
									</div>
									<div class="card-body">
									<form action="{{ route('profile.user.company.save', $user->id) }}" method="POST">
										@csrf
										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="company_name">Denumire firma</label>
													<input type="text" class="form-control @error('company_name') has-error @enderror" id="company_name" name="company_name" placeholder="Firma Mea SRL" value="{{ old('company_name') ?? $company->name }}">
													@error('company_name')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="year_made">An infiintare</label>
													<input type="numeric" class="form-control @error('year_made') has-error @enderror" id="year_made" name="year_made" placeholder="2002" value="{{ old('year_made') ?? $company->year_made }}">
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
													<input type="text" class="form-control @error('phone') has-error @enderror" id="phone" name="phone" placeholder="0740000000" value="{{ old('phone') ?? $company->phone }}">
													@error('phone')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="workers">Numar angajati</label>
													<input type="numeric" class="form-control @error('workers') has-error @enderror" id="workers" name="workers" placeholder="10" value="{{ old('workers') ?? $company->workers }}">
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
													<input type="numeric" class="form-control @error('cui') has-error @enderror" id="cui" name="cui" placeholder="12345678" value="{{ old('cui') ?? $company->cui }}">
													@error('cui')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="register_number">Numar Inmatriculare</label>
													<input type="text" class="form-control @error('register_number') has-error @enderror" id="register_number" name="register_number" placeholder="J28/123/2008" value="{{ old('register_number') ?? $company->register_number }}">
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
													<input type="text" class="form-control @error('administrative_company') has-error @enderror" id="administrative_company" name="administrative_company" placeholder="Olt" value="{{ old('administrative_company') ?? $company->administrative }}">
													@error('administrative_company')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="form-group">
													<label for="city_company">Oras</label>
													<input type="text" class="form-control @error('city_company') has-error @enderror" id="city_company" name="city_company" placeholder="Corabia" value="{{ old('city_company') ?? $company->city }}">
													@error('city_company')
															<p class="small text-danger">{{ $message }}</p>
													@enderror
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="address_company">Adresa</label>
											<input type="text" class="form-control @error('address_company') has-error @enderror" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" value="{{ old('address_company') ?? $company->address }}">
											@error('address_company')
													<p class="small text-danger">{{ $message }}</p>
											@enderror
										</div>

										<div class="form-group">
											<label class="form-label" for="bio">Descriere companie</label>
											<textarea name="bio" class="form-control @error('bio') has-error @enderror" rows="6" placeholder="Scurta descriere a companiei...">{{ old('bio') ?? $company->bio }}</textarea>
											@error('bio')
													<p class="small text-danger">{{ $message }}</p>
											@enderror
										</div>

										<div class="form-group">
											<label for="website" class="form-label">Site internet</label>
											<input type="text" class="form-control @error('website') has-error @enderror" name="website" placeholder="www.website.ro" value="{{ old('website') ?? $company->website }}">
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
							</div><!-- end col-lg-8 -->
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