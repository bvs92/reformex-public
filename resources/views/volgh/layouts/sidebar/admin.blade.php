<ul class="side-menu">

    <li><h3>Acasă</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('home') }}"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">Pagină de start</span></a>
    </li>

    <li><h3>Anunțuri globale</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('announcement.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Anunțuri</span></a>
    </li>

    <li><h3>Categorii</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('categories.index.vue') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Categorii</span></a>
    </li>


    <li><h3>Cereri</h3></li>

    {{-- <li>
        <a class="side-menu__item" href="{{ route('demands.register.vue') }}"><i class="side-menu__icon ti-plus"></i><span class="side-menu__label">Adauga proiect VUE</span></a>
    </li> --}}

    {{-- <li>
        <a class="side-menu__item" href="{{ route('demands.client.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Proiecte lansate</span></a>
    </li> --}}


    {{-- <li>
        <a class="side-menu__item" href="{{ route('demands.explore.vue') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Exploreaza Cereri</span></a>
    </li> --}}

    <li>
        <a class="side-menu__item" href="{{ route('demands.explore.vue.final') }}"><i class="side-menu__icon ti-world"></i><span class="side-menu__label">Explorează</span></a>
    </li>



    <li>
        <a class="side-menu__item" href="{{ route('admin.demands.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Toate cererile</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('admin.demands.reported.list.all') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Cereri reclamate</span></a>
    </li>



    <li><h3>Companii</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('companies.create') }}"><i class="side-menu__icon ti-plus"></i><span class="side-menu__label">Adaugă companie</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('companies.pending') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cereri înscriere</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('company_reviews.index') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii</span></a>
    </li>

    <li><h3>Cupoane</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('coupons.index') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Cupoane</span></a>
    </li>

    <li>
        <a class="side-menu__item" href="{{ route('coupons.requests.all') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Solicitări</span></a>
    </li>


    <li><h3>Publicitate</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('advertising.periods.index') }}"><i class="side-menu__icon ti-time"></i><span class="side-menu__label">Perioade valabilitate</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('advertising.banners.index') }}"><i class="side-menu__icon ti-announcement"></i><span class="side-menu__label">Banner</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('advertising.admin.ad_recommend.index') }}"><i class="side-menu__icon ti-announcement"></i><span class="side-menu__label">Anunțuri firme recomandate</span></a>
    </li>

    

    <li><h3>Utilizatori</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('roles.index') }}"><i class="side-menu__icon ti-settings"></i><span class="side-menu__label">Roluri</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('users.all') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Utilizatori</span></a>
    </li>

    <li><h3>Proiecte lucrări</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('work-project-categories.index') }}"><i class="side-menu__icon ti-package"></i><span class="side-menu__label">Categorii proiecte</span></a>
    </li>


    <li><h3>Plăți & facturi</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('payments.all') }}"><i class="side-menu__icon ti-receipt"></i><span class="side-menu__label">Plăți utilizatori</span></a>
        <a class="side-menu__item" href="{{ route('invoices.index') }}"><i class="side-menu__icon ti-receipt"></i><span class="side-menu__label">Facturi utilizatori</span></a>
    </li>


    <li><h3>Recenzii</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('reviews.all') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('reviews.reported') }}"><i class="side-menu__icon ti-star"></i><span class="side-menu__label">Recenzii reclamate</span></a>
    </li>


    <li><h3>Setări</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('user.profile.settings.personal') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Informații principale</span></a>
    </li>
    <li>
        <a class="side-menu__item" href="{{ route('keys.index') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Chei API</span></a>
    </li>
 

    <li>
        <a class="side-menu__item" href="{{ route('notifications.all.vue') }}"><i class="side-menu__icon ti-bell"></i><span class="side-menu__label">Notificări</span></a>
    </li>


    {{-- <li><h3>Suport</h3></li>
    <li>
        <a class="side-menu__item" href="{{ route('tickets.index') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Tichete</span></a>
    </li>

    <li>
        <a class="side-menu__item" href="{{ route('tickets.list.all') }}"><i class="side-menu__icon ti-comment-alt"></i><span class="side-menu__label">Tichete - vue</span></a>
    </li> --}}
    

</ul>