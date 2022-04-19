<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        <!-- CSRF Token -->
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
       
        <!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Cont inactiv">
        <title>Cont inactiv</title>
        @include('volgh.layouts.head')    
        @yield('head-scripts')   
    </head>

    <body class="app sidebar-mini" >
        @include('volgh._includes.errors')
        @include('volgh._includes.session-flash')
        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="{{URL::asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">    
        </div>
        <!-- /GLOBAL-LOADER -->
        <!-- PAGE -->
         <div class="page" id="app">
         <div class="page-main">
            @include('volgh.layouts.app-sidebar-inactive')
            @include('volgh.layouts.mobile-header-inactive')
        <div class="app-content">
        <div class="side-app">
        <div class="page-header">    
            @yield('page-header')
            @include('volgh.layouts.notification-inactive')  
        </div> 
            @yield('content')
            @include('volgh.layouts.sidebar')  
            @include('volgh.layouts.footer')   
        </div>
        </div>
            @include('volgh.layouts.footer-scripts')  
            @yield('footer-scripts')
    </body>
</html>