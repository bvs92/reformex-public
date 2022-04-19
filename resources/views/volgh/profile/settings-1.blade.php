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
                </div>
                
                @if(auth()->user()->hasRole('standard') || auth()->user()->hasRole('professional') && !auth()->user()->isPro())
                <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">

                    <div class="row">

                        <div class="card">
                            <div class="card-body text-center row justify-content-center">
                                <h2>Activati modul PROFESIONIST pentru a contacta clientii.</h2>
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

                    <!-- start tabs --><div class="panel panel-primary">
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
											
											<div class="tab-pane" id="tab1">
												@if($user->isPro())
                                                <!-- start card --><div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Setari modul PROFESIONIST</h3>
                                                    </div>
                                                    <div class="card-body">

                                                        <form method="POST" action="{{ route('professionals.updatePro') }}">
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
                                                @endif {{-- end if PRO --}}
											</div>
											<div class="tab-pane" id="tab2">
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
                                                </div><!-- end card company -->
											</div>
										</div>
									</div>
								</div><!-- end tabs -->



                    @if($user->isPro())
                    <!-- start card --><div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Setari modul PROFESIONIST</h3>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="{{ route('professionals.updatePro') }}">
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
                    </div><!-- end card company -->


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