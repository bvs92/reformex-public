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
        <title>REFORMEX</title>
        @include('volgh.layouts.head')    
        @yield('head-scripts')   
    </head>

    <body>
        <!-- GLOBAL-LOADER -->
        {{-- <div id="global-loader">
            <img src="{{URL::asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">    
        </div> --}}
        <!-- /GLOBAL-LOADER -->
        <!-- PAGE -->
         {{-- <div class="page">
         <div class="page-main"> --}}

        <div class="app-content" style="max-width: 1400px;margin: 0 auto; display: block;">
            <br><br>
            {{-- <div class="side-app"> --}}
            <div>
                @yield('content')
            </div>
        </div>
        @include('volgh.layouts.footer-scripts')  
        @yield('footer-scripts')
    </body>
</html>