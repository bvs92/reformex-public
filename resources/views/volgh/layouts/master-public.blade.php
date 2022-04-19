<!DOCTYPE html>
<html lang="ro" dir="ltr">
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


        @include('volgh.layouts.head-public')    
        @yield('head-scripts')   
    </head>

    <body style="background: white;">
        <!-- GLOBAL-LOADER -->
        {{-- <div id="global-loader">
            <img src="{{URL::asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">    
        </div> --}}
        <!-- /GLOBAL-LOADER -->
        <!-- PAGE -->
         <div class="page" id="app">
         <div class="page-main">

        <div class="app-content" style="margin-left: 0px!important;">
            <br><br>
            {{-- <div class="side-app"> --}}
            <div>
                @yield('content')
                @include('volgh.layouts.footer')   
            </div>
        </div>
            @include('volgh.layouts.footer-scripts-public')  
            @yield('footer-scripts')
            
    </body>
</html>