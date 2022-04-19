        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('assets/images/brand/favicon.ico')}}" />

        <!-- TITLE -->
        <title>REFORMEX</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- BOOTSTRAP CSS -->
        <link href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />

        <!-- STYLE CSS -->
        <link href="{{ URL::asset('assets/css/style.css')}}" rel="stylesheet"/>
        <link href="{{ URL::asset('assets/css-dark/dark-style.css')}}" rel="stylesheet"/>
        <link href="{{ URL::asset('assets/css/skin-modes.css')}}" rel="stylesheet"/>
        
        <!-- SIDE-MENU CSS -->
        <link href="{{ URL::asset('assets/plugins/sidemenu/sidemenu.css')}}" rel="stylesheet">

        <!--C3.JS CHARTS PLUGIN -->
        {{-- <link href="{{ URL::asset('assets/plugins/charts-c3/c3-chart.css')}}" rel="stylesheet"/> --}}
        
        @yield('css')

        <!-- CUSTOM SCROLL BAR CSS-->
        <link href="{{ URL::asset('assets/plugins/scroll-bar/jquery.mCustomScrollbar.css')}}" rel="stylesheet"/>

        <!--- FONT-ICONS CSS -->
        <link href="{{ URL::asset('assets/css/icons.css')}}" rel="stylesheet"/>

        <!-- SIDEBAR CSS -->
        <link href="{{ URL::asset('assets/plugins/sidebar/sidebar.css')}}" rel="stylesheet">

        <!-- COLOR SKIN CSS -->
        <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ URL::asset('assets/colors/color1.css')}}" />

<style>
.sidebar-mini.sidenav-toggled .side-menu h3 {
        display: initial!important;
}

ul.side-menu li {
        margin: 5px 0px;
}
</style>