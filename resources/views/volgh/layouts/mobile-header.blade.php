<!-- Mobile Header -->
                <div class="mobile-header" style="position: relative!important;">
                    <div class="container-fluid">
                        <div class="d-flex">
                            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->

                            <a class="header-brand" href="{{ url('/start') }}">
                                {{-- <img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-logo" alt="logo">
                                <img src="{{URL::asset('assets/images/brand/logo-3.png')}}" class="header-brand-img desktop-logo mobile-light" alt="logo"> --}}
                                <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" class="header-brand-img desktop-logo mobile-light" alt="reformex" >
                            </a>
                            
                            <div class="d-flex order-lg-2 ml-auto header-right-icons">
                                {{-- <button class="navbar-toggler navresponsive-toggler d-md-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4"
                                    aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon fe fe-more-vertical text-white"></span>
                                </button> --}}


                                <div class="dropdown profile-1">
									<a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
										<span>
											@if(auth()->user()->hasProfilePhoto())
												<img src="{{ asset(auth()->user()->getFullThumbnailProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="avatar profile-user brround cover-image">
											@else
												<img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ auth()->user()->getName() }}" class="avatar profile-user brround cover-image">
											@endif
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<div class="drop-heading">
											<div class="text-center">
												<h5 class="text-dark mb-0">{{ auth()->user()->getName() }}</h5>
												<small class="text-muted">@if(auth()->user()->hasRoles()) {{ ucfirst(auth()->user()->getFirstRole()) }} @endif</small>
											</div>
										</div>
										<div class="dropdown-divider m-0"></div>
										<a class="dropdown-item" href="{{ route('user.profile.settings.personal') }}">
											<i class="dropdown-icon mdi mdi-account-outline"></i> Profil
										</a>

										@role('professional')
										{{-- <a class="dropdown-item" href="{{ route('user.profile.settings') }}">
											<i class="dropdown-icon  mdi mdi-settings"></i> Setari profesionist
										</a> --}}

										<a class="dropdown-item" href="{{ route('credits.simple') }}">
											<i class="dropdown-icon mdi mdi-currency-usd"></i> Credit
										</a>

										{{-- <a class="dropdown-item" href="{{ route('settings.pro.module') }}">
											<i class="dropdown-icon  mdi mdi-settings"></i> Setari profesionist
										</a> --}}
										@endrole


										{{-- <a class="dropdown-item" href="{{ route('notifications.messages') }}">
											<span class="float-right"></span>
											<i class="dropdown-icon mdi  mdi-message-outline"></i> Mesaje
										</a> --}}
										{{-- <a class="dropdown-item" href="#">
											<i class="dropdown-icon mdi mdi-comment-check-outline"></i> Message
										</a>
										<div class="dropdown-divider mt-0"></div>
										<a class="dropdown-item" href="#">
											<i class="dropdown-icon mdi mdi-compass-outline"></i> Need help?
										</a> --}}

										<!-- initial logout -->
										{{-- <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">
											<i class="dropdown-icon mdi  mdi-logout-variant"></i> {{ __('Delogare') }}
										</a>

										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											@csrf
										</form> --}}

									<!-- end initial logout -->

										<logout-component></logout-component>
									
									</div>
								</div><!-- end profile dropdown -->


                                


                                <div class="row dropdown d-md-flex header-settings">
									<unread-notifications-header></unread-notifications-header>
								</div>

                                {{-- <div class="dropdown d-md-flex header-settings">
                                    <a href="#" class="nav-link icon " data-toggle="sidebar-right" data-target=".sidebar-right">
                                        <i class="fe fe-align-right"></i>
                                    </a>
                                </div> --}}
                                <!-- SIDE-MENU -->
                            </div>
                        </div>
                    </div>
                </div>
                

                {{-- <div class="mb-1 navbar navbar-expand-lg  responsive-navbar navbar-dark d-md-none bg-white">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                        <div class="d-flex order-lg-2 ml-auto">
                            <unread-messages-notifications-component></unread-messages-notifications-component>
                        </div>
                    </div>
                </div> --}}
<!-- /Mobile Header -->
