<!-- /Notification -->
<div class="d-flex  ml-auto header-right-icons header-search-icon">

    <!-- SEARCH -->
    <div class="dropdown d-md-flex">
        <a class="nav-link icon full-screen-link nav-link-bg">
            <i class="fe fe-maximize fullscreen-button"></i>
        </a>
    </div><!-- FULL-SCREEN -->


    <div class="dropdown profile-1 mx-2">
        {{-- <a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
            <span>
                @if(auth()->user()->hasProfilePhoto())
                    <img src="{{ asset(auth()->user()->getFullThumbnailProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="avatar profile-user brround cover-image">
                @else
                    <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ auth()->user()->getName() }}" class="avatar profile-user brround cover-image">
                @endif
            </span>
        </a> --}}
        <a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
            <sidebar-top-profile-photo-component :type="'top'"></sidebar-top-profile-photo-component>
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

    <div class="row">
        {{-- <unread-notifications-header></unread-notifications-header> --}}
    </div>

    <!-- SIDE-MENU -->
</div>
<!-- /Notification Ends -->
