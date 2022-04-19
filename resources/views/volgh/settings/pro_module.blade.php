@extends('volgh.layouts.master')

@section('head-scripts')

@endsection


@section('css')

@endsection
@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Setari profesionist</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Acasa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Setari profesionist</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                
                @if(auth()->user()->hasRole('standard') || auth()->user()->hasRole('professional') && !auth()->user()->isPro())
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">

                    <activate-pro-account-component></activate-pro-account-component>

                    <div class="row">

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Cum va ajuta modulul de profesionist?</h2>
                                <div class="card-options">
                                  
                                </div>
                            </div>

                            <div class="card-body">
                                
                                <h3>De ce sa activezi modulul de profesionist?</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam, atque dolores animi alias perferendis vero facere eligendi reiciendis autem vel a saepe excepturi neque qui aperiam voluptate impedit minima iste praesentium non laboriosam assumenda tempora voluptatum! Eos unde adipisci doloribus!</p>
                                <br><br>
                                <h3>Care sunt avantajele acestui modul?</h3>
                                <ul class="list-group">
                                    <li class="list-group-item">Lorem ipsum dolor sit amet.</li>
                                    <li class="list-group-item">Lorem ipsum dolor sit amet.</li>
                                    <li class="list-group-item">Lorem ipsum dolor sit amet.</li>
                                    <li class="list-group-item">Lorem ipsum dolor sit amet.</li>
                                    <li class="list-group-item">Lorem ipsum dolor sit amet.</li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- end of is PRO -->

                </div>
                @else
                
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Setari modul profesionist</h2>
                            <div class="card-options">
                              
                            </div>
                        </div>

                        <div class="card-body">
                            
                            @if(auth()->user()->isPro())
                                <profile-places-component :appid="{{config('services.algolia.appId')}}" :appkey="{{config('services.algolia.apiKey')}}" :the_accessTokenMap="{{ json_encode(config('services.mapbox.api_key')) }}"></profile-places-component>
                                <hr>

                                <hr>
                                <h4>Selectati categoriile de interes</h4>
                                <categories-profile-component :inc_categories="{{ json_encode($categories) }}" :my_categories="{{ json_encode($my_categories) }}"></categories-profile-component>
                            @endif {{-- end if PRO --}}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- ROW-1 CLOSED -->


        </div>
    </div>
    <!--CONTAINER CLOSED -->
</div>
@endsection
@section('js')
{{-- <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script> --}}
@endsection

@section('footer-scripts')



@endsection