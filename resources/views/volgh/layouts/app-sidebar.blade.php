<!--APP-SIDEBAR-->
                <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
                <aside class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="{{ url('/start') }}">
                            <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" class="header-brand-img light-logo1" alt="reformex" >
                        </a><!-- LOGO -->
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle ml-auto" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->
                    </div>
                    <div class="app-sidebar__user">
                        <div class="dropdown user-pro-body text-center">
                            <sidebar-top-profile-photo-component :type="'sidebar'"></sidebar-top-profile-photo-component>
                            {{-- <div class="user-pic">
                                @if(auth()->user()->hasProfilePhoto())
                                    <img src="{{ asset(auth()->user()->getFullThumbnailProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="avatar-xl rounded-circle">
                                @else
                                <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ auth()->user()->getName() }}" class="avatar-xl rounded-circle">
                                @endif
                            </div> --}}
                            <div class="user-info">
                            <h6 class=" mb-0 text-dark">{{ auth()->user()->getName() }}</h6>
                                <span class="text-muted app-sidebar__user-name text-sm">@if(auth()->user()->hasRoles()) {{ ucfirst(auth()->user()->getFirstRole()) }} @endif</span>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                        @include('volgh.layouts.sidebar.admin')
                    @elseif(auth()->user()->isPro())
                        @include('volgh.layouts.sidebar.pro')
                    @endif
                </aside>
<!--/APP-SIDEBAR-->
