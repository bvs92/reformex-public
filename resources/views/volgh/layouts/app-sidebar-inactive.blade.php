<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ url('/' . $page='index') }}">
            <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" class="header-brand-img light-logo1" alt="reformex" >
        </a><!-- LOGO -->
        <a aria-label="Hide Sidebar" class="app-sidebar__toggle ml-auto" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->
    </div>
    <div class="app-sidebar__user">
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                @if(auth()->user()->hasProfilePhoto())
                    <img src="{{ asset(auth()->user()->getFullThumbnailProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="avatar-xl rounded-circle">
                @else
                <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ auth()->user()->getName() }}" class="avatar-xl rounded-circle">
                @endif
            </div>
            <div class="user-info">
            <h6 class=" mb-0 text-dark">{{ auth()->user()->getName() }}</h6>
            </div>
        </div>
    </div>
    {{-- <ul class="side-menu">
        <li><h3>Suport</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('tickets.get.all') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Tichete</span></a>
        </li>
        

    </ul> --}}
</aside>
<!--/APP-SIDEBAR-->
