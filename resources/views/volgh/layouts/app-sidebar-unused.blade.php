<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ url('/' . $page='index') }}">
            {{-- <img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{URL::asset('assets/images/brand/logo-1.png')}}"  class="header-brand-img toggle-logo" alt="logo">
            <img src="{{URL::asset('assets/images/brand/logo-2.png')}}" class="header-brand-img light-logo" alt="logo"> --}}
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
                <span class="text-muted app-sidebar__user-name text-sm">@if(auth()->user()->hasRoles()) {{ ucfirst(auth()->user()->getFirstRole()->name) }} @endif</span>
            </div>
        </div>
    </div>
    <div class="sidebar-navs">
        <ul class="nav  nav-pills-circle">
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Setari">
                <a href="{{ route('user.profile.settings') }}" class="nav-link text-center m-2">
                    <i class="fe fe-settings"></i>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Mesaje">
                <a href="{{ route('notifications.messages') }}" class="nav-link text-center m-2">
                    <i class="fe fe-mail"></i>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Profil">
                <a href="{{ route('user.profile') }}" class="nav-link text-center m-2">
                    <i class="fe fe-user"></i>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Delogare">
                <a  class="nav-link text-center m-2" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    <i class="fe fe-power"></i>
                </a>
            </li>
        </ul>
    </div>
    <ul class="side-menu">

        <li><h3>Acasa</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('home') }}"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">Pagina de start</span></a>
        </li>

        @role('admin')
        <li><h3>Categorii</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('categories.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Categorii</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('categories.index.vue') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Categorii - Vue</span></a>
        </li>
        @endrole

        <li><h3>Proiecte</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('demands.register') }}"><i class="side-menu__icon ti-plus"></i><span class="side-menu__label">Adauga proiect</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.register.vue') }}"><i class="side-menu__icon ti-plus"></i><span class="side-menu__label">Adauga proiect VUE</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.client.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Proiecte lansate</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.explore') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Exploreaza Cereri</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.explore.vue') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Exploreaza Cereri VUE</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.explore.algolia') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Exploreaza cu Algolia</span></a>
        </li>



        

        @role('professional')

        <li>
            <a class="side-menu__item" href="{{ route('demands.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Cereri</span></a>
        </li>
        
        <li>
            <a class="side-menu__item" href="{{ route('demands.unlocked') }}"><i class="side-menu__icon ti-unlock"></i><span class="side-menu__label">Deblocate</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.personal') }}"><i class="side-menu__icon ti-target"></i><span class="side-menu__label">Personalizate</span></a>
        </li>

        @endrole
        
        
        
        @role('admin|professional')
        <li>
            <a class="side-menu__item" href="{{ route('demands_reports.index') }}"><i class="side-menu__icon ti-alert"></i><span class="side-menu__label">Raportari</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.reported') }}"><i class="side-menu__icon ti-alert"></i><span class="side-menu__label">Cereri Raportate</span></a>
        </li>
        @endrole

        @role('admin')
        <li>
            <a class="side-menu__item" href="{{ route('admin.demands.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Lista cereri (Vue - Admin)</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('admin.demands.reported.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Lista cereri raportate (Vue - Admin)</span></a>
        </li>
        @endrole
        
        <li>
            <a class="side-menu__item" href="{{ route('demands.pro.reported.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Lista cereri raportate (Vue - PRO)</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('demands.pro.unlocked.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Lista cereri deblocate (Vue - PRO)</span></a>
        </li>

        <li><h3>Activitate</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('timeline.index.client') }}"><i class="side-menu__icon ti-comments"></i><span class="side-menu__label">Conversatii cu firme</span></a>
        </li>

        @role('professional')
        <li>
            <a class="side-menu__item" href="{{ route('timeline.index.pro') }}"><i class="side-menu__icon ti-comments"></i><span class="side-menu__label">Conversatii cu clienti</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('quotes.personal') }}"><i class="side-menu__icon ti-agenda"></i><span class="side-menu__label">Cotatiile mele</span></a>
        </li>
        @endrole


        @role('professional')
        <li><h3>Facturare</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('credits.personal') }}"><i class="side-menu__icon ti-notepad"></i><span class="side-menu__label">Balanta - Personal (vue) bun</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('credits.index') }}"><i class="side-menu__icon ti-notepad"></i><span class="side-menu__label">Balanta</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('payments.vue') }}"><i class="side-menu__icon ti-credit-card"></i><span class="side-menu__label">Plata (bun - VUE)</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('payments.index') }}"><i class="side-menu__icon ti-credit-card"></i><span class="side-menu__label">Plati</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('invoices.all') }}"><i class="side-menu__icon ti-credit-card"></i><span class="side-menu__label">Facturi Stripe</span></a>
        </li>
        

        <li>
            <a class="side-menu__item" href="{{ route('charges.index') }}"><i class="side-menu__icon ti-credit-card"></i><span class="side-menu__label">Incasari - facturi (BUN)</span></a>
        </li>


        <li>
            <a class="side-menu__item" href="{{ route('refundsdemands.index') }}"><i class="side-menu__icon ti-back-left"></i><span class="side-menu__label">Rambursari</span></a>
        </li>
        @endrole

        @role('professional')
        <li><h3>Cupoane</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('coupons.requests') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cupoane solicitate</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('coupons.personal') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cupoane primite</span></a>
        </li>

        <li><h3>Activitate</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('activities.personal') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Activitate (PRO) - vue</span></a>
        </li>

        @endrole

        @role('admin') 
        <li><h3>Companii</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('companies.create') }}"><i class="side-menu__icon ti-plus"></i><span class="side-menu__label">Adauga companie</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('companies.pending') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cereri inscriere</span></a>
        </li>

        <li><h3>Cupoane</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('coupons.index') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cupoane</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('coupons.requests.all') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Solicitari</span></a>
        </li>

        <li><h3>Utilizatori</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('permissions.index') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Permisiuni</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('roles.index') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Roluri</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('users.index') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Utilizatori</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('users.all') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Utilizatori (Vue)</span></a>
        </li>


        <li>
            <a class="side-menu__item" href="{{ route('work-projects-categories.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Categorii Proiecte</span></a>
            <a class="side-menu__item" href="{{ route('work-projects.index') }}"><i class="side-menu__icon ti-gallery"></i><span class="side-menu__label">Proiecte lucrari</span></a>
        </li>

        <li><h3>Recenzii</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('reviews.all') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('reviews.reported') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii reclamate</span></a>
        </li>
        @endrole

        <li><h3>Setari</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('user.profile.settings.personal') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Informatii principale</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('user.profile.settings.company') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Informatii companie</span></a>
        </li>
        {{-- <li>
            <a class="side-menu__item" href="{{ route('user.profile.company') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Informatii firma</span></a>
        </li> --}}
        <li>
            <a class="side-menu__item" href="{{ route('settings.pro.module') }}"><i class="side-menu__icon ti-notepad"></i><span class="side-menu__label">Modul profesionist</span></a>
        </li>
        @role('professional')
        <li>
            <a class="side-menu__item" href="#"><i class="side-menu__icon ti-credit-card"></i><span class="side-menu__label">Plata</span></a>
        </li>
        @endrole
        <li>
            <a class="side-menu__item" href="#"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cont</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('notifications.settings') }}"><i class="side-menu__icon ti-announcement"></i><span class="side-menu__label">Notificari</span></a>
        </li>

        @role('professional')
        <li>
            <a class="side-menu__item" href="{{ route('user.profile.reviews.personal') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii</span></a>
        </li>
        @endrole


        <li><h3>Suport</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('tickets.index') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Tichete</span></a>
        </li>

        <li>
            <a class="side-menu__item" href="{{ route('tickets.list.all') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Tichete - vue</span></a>
        </li>
        

    </ul>
</aside>
<!--/APP-SIDEBAR-->
