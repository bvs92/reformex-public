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
                                                @if($user->hasProfilePhoto())
                                                    <img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" width="180px">
                                                @else
                                                    <img class="" src="{{URL::asset('assets/images/users/10.jpg')}}" alt="img">
                                                @endif
                                            </div>
                                            <div class="user-wrap">
                                                @if($user->isPro())
                                                <h4>{{ $user->professional->name }}</h4>
                                                @else
                                                <h4>{{ $user->getName() }}</h4>
                                                @endif
                                                <h6 class="text-muted mb-3">Inregistrat la data: {{ formatCarbonDate($user->created_at) }}</h6>
                                                <a href="#" class="btn btn-primary mt-1 mb-1"><i class="fa fa-rss"></i> Follow</a>
                                                <a href="#" class="btn btn-secondary mt-1 mb-1"><i class="fa fa-envelope"></i> E-mail</a>
                                                <a href="{{ route('user.admin.profile.edit', $user->id) }}" class="btn btn-warning mt-1 mb-1"><i class="fa fa-edit"></i> Editare profil</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="wideget-user-info">
                                            <div class="wideget-user-warap">
                                                <div class="wideget-user-warap-l">
                                                    <h4 class="text-danger">7253</h4>
                                                    <p>Total Items</p>
                                                </div>
                                                <div class="wideget-user-warap-r">
                                                    <h4 class="text-danger">8432</h4>
                                                    <p>Total Sales</p>
                                                </div>
                                            </div>
                                            @if($user->isPro())
                                                @if($user->professional->reviews && $user->professional->reviews->count() > 0)
                                                    <div class="wideget-user-rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $user->professional->getRating())
                                                                <a href="#"><i class="fa fa-star text-warning"></i></a>
                                                            @else
                                                                <a href="#"><i class="fa fa-star-o text-warning mr-1"></i></a> 
                                                            @endif
                                                        @endfor
                                                        <span>{{ $user->professional->getRating() }} din 5 stele ({{ $user->professional->reviews->count() }} @if($user->professional->reviews->count() == 1) Recenzie @else Recenzii @endif)</span>
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="wideget-user-icons">
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

                                                @if($user->existsSocialProfile('tiktok_profile'))
                                                <a href="https://www.tiktok.com/{{ $user->getSocialProfile('tiktok_profile')->username }}" class="bg-dark text-white" target="_blank">
                                                    <i class="fab fa-tiktok"></i>T</a> 
                                                @endif
                                                
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
                                            <li class=""><a href="#tab-51" class="active show" data-toggle="tab">Profil</a></li>
                                            <li><a href="#tab-62" data-toggle="tab" class="">Drepturi</a></li>
                                            <li><a href="#tab-63" data-toggle="tab" class="">Proiecte</a></li>
                                            <li><a href="#tab-61" data-toggle="tab" class="">Friends</a></li>
                                            <li><a href="#tab-71" data-toggle="tab" class="">Gallery</a></li>
                                            <li><a href="#tab-81" data-toggle="tab" class="">Followers</a></li>
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
                                                <h5><strong>Informatii</strong></h5>
                                            </div>
                                            <div class="table-responsive ">
                                                <table class="table row table-borderless">
                                                    <tbody class="col-lg-12 col-xl-6 p-0">
                                                        <tr>
                                                            <td><strong>Nume complet:</strong> {{ $user->getName() }}</td>
                                                        </tr>
                                                        @if($user->isCompany())
                                                        <tr>
                                                            @if($user->company->year_made)
                                                                <td><strong>Data infiintare:</strong> {{ $user->company->year_made }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->name)
                                                                <td><strong>Nume firma:</strong> {{ $user->company->name }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->workers)
                                                                <td><strong>Numar angajati:</strong> {{ $user->company->workers }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->cui)
                                                                <td><strong>Cod Unic de Inregistrare:</strong> {{ $user->company->cui }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->register_number)
                                                                <td><strong>Numar Inregistrare:</strong> {{ $user->company->register_number }}</td>
                                                            @endif
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                    <tbody class="col-lg-12 col-xl-6 p-0">
                                                        <tr>
                                                            <td><strong>Email :</strong> {{ $user->email }}</td>
                                                        </tr>
                                                        @if($user->isCompany())
                                                        <tr>
                                                            @if($user->company->website)
                                                                <td><strong>Site internet:</strong> {{ $user->company->website }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->phone)
                                                                <td><strong>Numar telefon:</strong> {{ $user->company->phone }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->administrative)
                                                                <td><strong>Judet:</strong> {{ $user->company->administrative }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->city)
                                                                <td><strong>Telefon:</strong> {{ $user->company->city }}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            @if($user->company->address)
                                                                <td><strong>Adresa:</strong> {{ $user->company->address }}</td>
                                                            @endif
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if($user->isCompany())
                                            <div class="row profie-img">
                                                <div class="col-md-12">
                                                    <div class="media-heading">
                                                        <h5><strong>Descriere firma</strong></h5>
                                                    </div>
                                                    <div>
                                                        {{ $user->company->bio }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab-62">
                                        
                                        <div class="card">

                                            <div class="card-body">
                                                <div class="form-group form-elements m-0">
                                                    <div class="form-label">Roluri</div>
                                                    @if($roles && $roles->count() > 0)
                                                   
                                                        <div class="custom-controls-stacked">
                                                            @foreach($roles as $role)
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" name="role-{{ $role->id }}" value="{{ $role->id }}" @if($user->roles->contains($role)) checked="checked" @endif disabled>
                                                                <span class="custom-control-label">{{ ucfirst($role->name) }}</span>
                                                            </label>
                                                            @endforeach
                                                        </div>

                                                    @endif
												</div>
                                            </div>

                                            <div class="card-body">
                                                @if($roles && $roles->count() > 0)
                                                <form method="POST" action="{{ route('users.roles', $user->id) }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="rolesNames">Selecteaza roluri.</label>
                                                                    <select class="form-control" id="rolesNames" name="name[]" multiple="multiple">
                                                                        @foreach($roles as $role)
                                                                            <option 
                                                                            value="{{ $role->id }}"
                                                                            @if($user->roles->contains($role)) selected="selected" @endif
                                                                            >{{ $role->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('name')
                                                                        <p class="small text-danger">{{ $message }}</p>
                                                                    @enderror
                                  
                                                            </div><!-- end form-group -->
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button type="submit" class="btn btn-primary mb-2 float-left">Salveaza modificari</button>
                                                            <button class="btn btn-danger mb-2 float-right" onclick="event.preventDefault();document.getElementById('resetRolesForm').submit();">Elimina roluri</button>
                                                        </div>
                                                    </div><!-- end row -->
                                                </form>
                                  
                                                <form id="resetRolesForm" action="{{ route('users.roles.reset', $user->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                @endif
                                            </div>
                                        </div>

                                    </div><!-- end drepturi -->


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

                                    <div class="tab-pane" id="tab-63">
                                        <div class="row">
                                        @if($user->projects && $user->projects->count())
                                            @foreach($user->projects as $project)
                                            <div class="col-lg-3 col-md-6">
                                                {{-- <img class="img-fluid rounded mb-5" src="{{URL::asset($project->firstPhoto())}}" alt="{{ $project->title }}"> --}}
                                                <a href="{{ route('work-projects.show', $project->id) }}">
                                                <img class="img-fluid rounded mb-5" src="{{$project->firstPhoto()}}" alt="{{ $project->title }}">
                                                <p class="text-center">{{ $project->title }}</p>
                                                </a>
                                            </div>
                                            @endforeach
                                        @else
                                            <p class="text-center">Nu exista proiecte adaugate.</p>
                                        @endif
                                        </div>
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
                                        <div class="row">
                                            <div class=" col-lg-6 col-md-12">
                                                <div class="card borderover-flow-hidden">
                                                    <div class="media card-body media-xs overflow-visible ">
                                                        <img class="avatar brround avatar-md mr-3" src="{{URL::asset('assets/images/users/18.jpg')}}" alt="avatar-img">
                                                        <div class="media-body valign-middle mt-2">
                                                            <a href="" class=" font-weight-semibold text-dark">John Paige</a>
                                                            <p class="text-muted ">johan@gmail.com</p>
                                                        </div>
                                                        <div class="media-body valign-middle text-right overflow-visible mt-2">
                                                            <button class="btn btn-primary" type="button">Follow</button> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-lg-6 col-md-12">
                                                <div class="card borderover-flow-hidden">
                                                    <div class="media card-body media-xs overflow-visible ">
                                                        <span class="avatar cover-image avatar-md brround bg-pink mr-3">LQ</span>
                                                        <div class="media-body valign-middle mt-2">
                                                            <a href="" class="font-weight-semibold text-dark">Lillian Quinn</a>
                                                            <p class="text-muted">lilliangore</p>
                                                        </div>
                                                        <div class="media-body valign-middle text-right overflow-visible mt-2">
                                                            <button class="btn btn-secondary" type="button">Follow</button> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-lg-6 col-md-12">
                                                <div class="card borderover-flow-hidden mb-lg-0">
                                                    <div class="media card-body media-xs overflow-visible ">
                                                        <span class="avatar cover-image avatar-md brround mr-3">IH</span>
                                                        <div class="media-body valign-middle mt-2">
                                                            <a href="" class="font-weight-semibold text-dark">Irene Harris</a>
                                                            <p class="text-muted">ireneharris@gmail.com</p>
                                                        </div>
                                                        <div class="media-body valign-middle text-right overflow-visible mt-2">
                                                            <button class="btn btn-success" type="button">Follow</button> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-lg-6 col-md-12">
                                                <div class="card borderover-flow-hidden mb-lg-0">
                                                    <div class="media card-body media-xs overflow-visible ">
                                                        <img class="avatar brround avatar-md mr-3" src="{{URL::asset('assets/images/users/2.jpg')}}" alt="avatar-img">
                                                        <div class="media-body valign-middle mt-2">
                                                            <a href="" class="text-dark font-weight-semibold">Harry Fisher</a>
                                                            <p class="text-muted mb-0">harryuqt</p>
                                                        </div>
                                                        <div class="media-body valign-middle text-right overflow-visible mt-2">
                                                            <button class="btn btn-danger" type="button">Follow</button> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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