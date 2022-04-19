<!-- Mobile Header -->
<div class="mobile-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->

            <a class="header-brand" href="{{ url('/' . $page='index') }}">
                <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" class="header-brand-img desktop-logo mobile-light" alt="reformex" >
            </a>
            
            <div class="d-flex order-lg-2 ml-auto header-right-icons">
                <button class="navbar-toggler navresponsive-toggler d-md-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4"
                    aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon fe fe-more-vertical text-white"></span>
                </button>
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
                            </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <logout-component></logout-component>
                    
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Mobile Header -->
