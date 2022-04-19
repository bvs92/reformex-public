<!-- /Notification -->
							<div class="d-flex  ml-auto header-right-icons header-search-icon">
								{{-- <div class="dropdown d-sm-flex">
									<a href="#" class="nav-link icon" data-toggle="dropdown">
										<i class="fe fe-search"></i>
									</a>
									<div class="dropdown-menu header-search dropdown-menu-left">
										<div class="input-group w-100 p-2">
											<input type="text" class="form-control " placeholder="Search....">
											<div class="input-group-append ">
												<button type="button" class="btn btn-primary ">
													<i class="fa fa-search" aria-hidden="true"></i>
												</button>
											</div>
										</div>
									</div>
								</div> --}}
								<!-- SEARCH -->
								{{-- <div class="dropdown d-md-flex">
									<a class="nav-link icon full-screen-link nav-link-bg">
										<i class="fe fe-maximize fullscreen-button"></i>
									</a>
								</div> --}}
								<!-- FULL-SCREEN -->


								{{-- <div class="dropdown d-md-flex notifications">
									<a class="nav-link icon" data-toggle="dropdown">
										<i class="fe fe-bell"></i>
										<span class="nav-unread badge badge-success badge-pill">2</span>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<div class="notifications-menu">
											<a class="dropdown-item d-flex pb-3" href="#">
												<div class="fs-16 text-success mr-3">
													<i class="fa fa-thumbs-o-up"></i>
												</div>
												<div class="">
													<strong>Someone likes our posts.</strong>
												</div>
											</a>
											<a class="dropdown-item d-flex pb-3" href="#">
												<div class="fs-16 text-primary mr-3">
													<i class="fa fa-commenting-o"></i>
												</div>
												<div class="">
													<strong>3 New Comments.</strong>
												</div>
											</a>
											<a class="dropdown-item d-flex pb-3" href="#">
												<div class="fs-16 text-danger mr-3">
													<i class="fa fa-cogs"></i>
												</div>
												<div class="">
													<strong>Server Rebooted</strong>
												</div>
											</a>
										</div>
										<div class="dropdown-divider"></div>
										<a href="#" class="dropdown-item text-center">View all Notification</a>
									</div>
								</div><!-- NOTIFICATIONS --> --}}




								{{-- <unread-messages-notifications-component></unread-messages-notifications-component> --}}







								<div class="dropdown profile-1">
									<a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
										<sidebar-top-profile-photo-component :type="'top'"></sidebar-top-profile-photo-component>
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
								</div><!-- end profile -->

								<div class="row">
									<unread-notifications-header></unread-notifications-header>
								</div>
								<!-- SIDE-MENU -->
							</div>
<!-- /Notification Ends -->
