@extends('volgh.layouts.master-normal')



@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<style>
	#mapid { height: 180px; }

span.tag {
	font-size: 14px; padding: 10px;
}
</style>
@endsection

@section('title-page')
@if($user->company)
<title>Profil {{ $user->company->name }}</title>
@else
<title>Profil {{ $user->getName() }}</title>
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
															<a href="https://www.facebook.com/{{ $user->getSocialProfile('facebook_profile')->username }}" class="bg-info text-white" target="_blank">
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
															
														</div>

														@if($user->badge)
															@if($user->badge->verified == true)
															<div class="d-flex direction-row mt-4">
																<div><img src="{{ asset('assets/images/badges/firma-verificata.png') }}" alt="firma verificata" class="avatar cover-image" style="background: none!important;"></div>
																<div><p class="text-center" style="font-size: 14px; padding: 6px;">Firmă verificată.</p></div>
															</div>
															@endif
														@endif

														{{-- <a href="#" class="btn btn-primary mt-3 mb-2"><i class="fa fa-envelope"></i> Contacteaza</a> --}}
													</div>

                                                    {{-- <div class="wideget-user-info">
                                                        <a href="#" class="btn btn-primary" ref="nofollow">Distribuie</a>
                                                    </div> --}}
												</div>
											</div>
										</div>
									</div>
									
								</div>




								<div class="card">
									<div class="card-body">
										
										<div class="row">
											<div class="col-lg-8">
												<div id="profile-log-switch">
													@if($public_profile)
													<div class="row profie-img mb-2">
														@if($public_profile->description)
														<div class="col-md-12">
															<div class="media-heading">
																<h2><strong>Descriere</strong></h2>
															</div>
															<div>
																{!! $public_profile->description !!}
															</div>
														</div>
														@endif
													</div>
													@endif

													<div class="row profie-img mt-6 mb-2">
														@if($categories && $categories->count() > 0)
														<div class="col-md-12">
															<div class="media-heading">
																<h2><strong>Categorii de lucru</strong></h2>
															</div>
															
															<div class="tags">
																@foreach($categories as $category)
																<span class="tag">{{ $category->name }}</span> 
																@endforeach
															</div>
															
														</div>
														@endif
													</div>

													@if($judete && $judete->count() > 0)
													<div class="row profie-img mt-6 mb-2">
														<div class="col-md-12">
															<div class="media-heading">
																<h2><strong>Zone de lucru</strong></h2>
															</div>
															
														</div>
														<div class="col-lg-12">
															
															<div class="tags my-2">
																@foreach($judete as $judet)
																<span class="tag tag-azure">{{ $judet->name }}</span>
																@endforeach
															</div>
															
														</div>

													</div>
													@endif

													

												</div>
											</div>
											<div class="col-lg-4">
												<div class="row profie-img mt-6 mb-2">
													@if($user->company)
													<div class="col-lg-12">
														<div class="media-heading">
															<h3>Informații complete firmă</h3>
														</div>
													</div>
													<div class="col-lg-12">
														<ul class="list-group">
														<li class="listunorder">Denumire firmă: <strong>{{ $user->company->name }}</strong></li>
														<li class="listunorder">Cod unic de înregistrare: <strong>{{ $user->company->cui }}</strong></li>
														<li class="listunorder">Număr de înregistrare (Registru Comerț): <strong>{{ $user->company->register_number }}</strong></li>
														</ul>
													</div>
													<div class="col-lg-12">
														<ul class="list-group">
														{{-- <li class="listunorder">
															An infiintare: <strong>{{ $user->company->year_made }}</strong>
														</li>
														<li class="listunorder">Numar angajati: <strong>{{ $user->company->workers }}</strong></li>
														<li class="listunorder">Adresa: <strong>{{ $user->company->address }}</strong></li> --}}
														@if($user->company->location)
														<li class="listunorder">Adresă: <strong>{{ $user->company->location->value }}</strong></li>
														@endif
														</ul>
													</div>
													@endif
													<div class="col-lg-12 mt-4">
														<div id='mapid'></div>
													</div>
												</div>
											</div>
										</div>
												
													
											
												
													<div class="row mt-10">
														<div class="col-lg-12">
															<h2>Portofoliu proiecte</h2>
														</div>
														@if($projects && $projects->count() > 0)
														@foreach($projects as $project)
														<div class="col-lg-3 col-md-6">
															@if($user->user_name_profile)
															<a href="/public/profil/profesionist/{{$user->user_name_profile->username}}/proiecte/{{$project->uuid}}">
															@else
															<a href="/public/profil/profesionist/{{$user->username}}/proiecte/{{$project->uuid}}">
															@endif
															@if($project->photos()->first())
															{{-- <img class="img-fluid rounded mb-3" src="{{URL::asset('storage/work_projects/' . $project->photos()->first()->name)}}" alt="banner image"> --}}
															<figure class="figure-stil" style="background: url({{URL::asset('storage/work_projects/' . $project->photos()->first()->name)}}) no-repeat center; background-size: cover;width: 100%;height: 360px!important;max-height: 360px!important;">
															</figure>
															@else
															<img class="img-fluid rounded mb-3" src="{{URL::asset('assets/images/media/8.jpg')}}" alt="banner image">
															@endif
															<p class="text-center" style="color: black;font-size: 14px;">{{ \Illuminate\Support\Str::words($project->title, 4, '...') }}</p>
															</a>
														</div>
														@endforeach
														@else
														<div class="col-lg-12">
															<p class="text-center">Acest profesionist nu are niciun proiect adaugăt.</p>
														</div>
														@endif
													</div><!-- end row -->
												
												
											
										
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
 <!-- Make sure you put this AFTER Leaflet's CSS -->
 {{-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script> --}}
   <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
<script>
    
	L.mapbox.accessToken = "{{ config('services.mapbox.api_key') }}";
    $lng = {{ $user->company->location->lng }};
    $lat = {{ $user->company->location->lat }};

    var mymap = L.map('mapid').setView([$lat, $lng], 12);

	L.marker([$lat, $lng], {
    icon: L.mapbox.marker.icon({
        'marker-size': 'small',
        'marker-symbol': 'marker',
        'marker-color': '#23b0a2'
    })
}).addTo(mymap);


    
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '',
        maxZoom: 20,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: "{{ config('services.mapbox.api_key') }}"
    }).addTo(mymap);
    
    

    
</script>

@endsection