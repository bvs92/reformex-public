<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        <!-- CSRF Token -->
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
       
        <!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="author" content="REFORMEX">
        <meta name="keywords" content="">

        @yield('title-page')
        {{-- <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCt1o_fnUkifD-fhVycE7vaeL_3PFBngds"></script> --}}
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt1o_fnUkifD-fhVycE7vaeL_3PFBngds&libraries=places">
        </script>
        
        @include('volgh.layouts.head')    
        @yield('head-scripts')   
    </head>

    <body class="app sidebar-mini" >
        @include('volgh._includes.errors')
        @include('volgh._includes.session-flash')
        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="{{URL::asset('assets/images/rfmx.gif')}}" class="loader-img" alt="Loader" style="top: 23%!important;">    
        </div>
        <!-- /GLOBAL-LOADER -->
        <!-- PAGE -->
         <div class="page" id="app">
         <div class="page-main">
            @include('volgh.layouts.app-sidebar')
            @include('volgh.layouts.mobile-header')
        <div class="app-content">
        <div class="side-app">
        <div class="page-header">    
            @yield('page-header')
            @include('volgh.layouts.notification')  
        </div> 
            @include('volgh.layouts.alerts')  
            @yield('content')
            @include('volgh.layouts.sidebar')  
            @include('volgh.layouts.footer')   
        </div>
        </div>
            @include('volgh.layouts.footer-scripts')  
            @yield('footer-scripts')
            
            @include('sweetalert::alert')
    </body>
</html>