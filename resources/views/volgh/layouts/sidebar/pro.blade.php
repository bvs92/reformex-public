<ul class="side-menu">

    <li><h3>Acasă</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('home') }}"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">Pagină de start</span></a>
    </li>

    <li><h3>Proiecte</h3></li>

    {{-- <li>
        <a class="side-menu__item" href="{{ route('demands.explore.vue') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Exploreaza Cereri VUE</span></a>
    </li> --}}

    <li>
        <a class="side-menu__item" href="{{ route('demands.explore.vue.final') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Explorează cereri</span></a>
    </li>

    <li>
        <a class="side-menu__item" href="{{ route('demands.pro.unlocked.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Cereri deblocate</span></a>
    </li>

    
    <li>
        <a class="side-menu__item" href="{{ route('demands.pro.reported.list.all') }}"><i class="side-menu__icon ti-alert"></i><span class="side-menu__label">Cereri reclamate</span></a>
    </li>

    

  
    {{-- <li><h3>Facturare</h3></li>
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
    </li> --}}
 


    <li><h3>Cupoane</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('coupons.personal') }}"><i class="side-menu__icon ti-gift"></i><span class="side-menu__label">Cupoane primite</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('coupons.requests') }}"><i class="side-menu__icon ti-briefcase"></i><span class="side-menu__label">Solicitări cupoane</span></a>
    </li>
    

    {{-- <li><h3>Activitate</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('activities.personal') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Activitate</span></a>
    </li> --}}

    <li><h3>Proiecte lucrări</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('work-projects.personal') }}"><i class="side-menu__icon ti-list"></i><span class="side-menu__label">Proiectele mele</span></a>
    </li>


    <li><h3>Contul meu</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('user.profile.settings.personal') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Setări cont</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('user.profile.settings.company') }}"><i class="side-menu__icon ti-info"></i><span class="side-menu__label">Informații companie</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('user.profile.settings.pro') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Informații profil public</span></a>
    </li>
    {{-- <li>
        <a class="side-menu__item" href="{{ route('user.profile.company') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Informatii firma</span></a>
    </li> --}}
    {{-- <li>
        <a class="side-menu__item" href="{{ route('settings.pro.module') }}"><i class="side-menu__icon ti-notepad"></i><span class="side-menu__label">Modul profesionist</span></a>
    </li> --}}

    <li>
        <a class="side-menu__item" href="{{ route('credits.simple') }}"><i class="side-menu__icon ti-credit-card"></i><span class="side-menu__label">Credit</span></a>
    </li>

    <li>
        <a class="side-menu__item" href="{{ route('payments.index') }}"><i class="side-menu__icon ti-receipt"></i><span class="side-menu__label">Facturare</span></a>
    </li>


    <li><h3>Publicitate</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('advertising.banners.personal.index') }}"><i class="side-menu__icon ti-announcement"></i><span class="side-menu__label">Banner</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('advertising.ad_recommend.personal.index') }}"><i class="side-menu__icon ti-announcement"></i><span class="side-menu__label">Anunțuri firme recomandate</span></a>
    </li>


    

    {{-- <li>
        <a class="side-menu__item" href="#"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cont</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('notifications.settings') }}"><i class="side-menu__icon ti-announcement"></i><span class="side-menu__label">Notificari</span></a>
    </li> --}}


    {{-- <li>
        <a class="side-menu__item" href="{{ route('user.profile.reviews.personal') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii</span></a>
    </li> --}}

    <li><h3>Notificări</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('notifications.all.vue') }}"><i class="side-menu__icon ti-bell"></i><span class="side-menu__label">Notificări</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('notification.settings.index') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Setări notificări</span></a>
    </li>

    <li><h3>Ajutor</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('help.index') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Am nevoie de ajutor</span></a>
    </li>
    {{-- <li>
        <a class="side-menu__item" href="{{ route('tickets.list.all') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Tichete - vue</span></a>
    </li> --}}
    

</ul>