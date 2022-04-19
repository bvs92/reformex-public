@extends('volgh.layouts.master')

@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('css')
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Editare Cotatie #{{ $quote->id }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quotes.personal') }}">Cotatii</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cotatie #{{ $quote->id }}</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-lg-4 col-xl-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="userprofile">
                            <div class="userpic brround"> 
                                @if(auth()->user()->hasProfilePhoto())
                                    <img src="{{ asset(auth()->user()->getFullProfilePhoto()) }}" alt="{{ auth()->user()->getName() }}" class="userpicimg">
                                @else
                                    <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="" class="userpicimg"> 
                                @endif
                            </div>
                            <h3 class="username text-dark mb-2">{{ auth()->user()->getName() }}</h3>
                            <p class="mb-1 text-muted">Administrator | {{ auth()->user()->professional->getLocation() }}</p>
                            <div class="text-center mb-4">
                                <span><i class="fa fa-star text-warning"></i></span>
                                <span><i class="fa fa-star text-warning"></i></span>
                                <span><i class="fa fa-star text-warning"></i></span>
                                <span><i class="fa fa-star-half-o text-warning"></i></span>
                                <span><i class="fa fa-star-o text-warning"></i></span>
                            </div>
                            <div class="socials text-center mt-3">
                                <a href="" class="btn btn-circle btn-primary ">
                                <i class="fa fa-facebook"></i></a> <a href="" class="btn btn-circle btn-danger ">
                                <i class="fa fa-google-plus"></i></a> <a href="" class="btn btn-circle btn-info ">
                                <i class="fa fa-twitter"></i></a> <a href="" class="btn btn-circle btn-warning "><i class="fa fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card panel-theme">
                <div class="card-header">
                    <div class="float-left">
                        <h3 class="card-title">Contact</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body no-padding">
                    <ul class="list-group no-margin">
                    <li class="list-group-item"><i class="fa fa-envelope mr-4"></i> {{ auth()->user()->email }}</li>
                    @if(auth()->user()->isPro())
                            <li class="list-group-item"><i class="fa fa-globe mr-4"></i> www.site.com</li>
                            <li class="list-group-item"><i class="fa fa-phone mr-4"></i> +125 5826 3658 </li>
                            <li class="list-group-item"><i class="fa fa-map-marker mr-4"></i> {{ auth()->user()->professional->getLocation() }} </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12">
            <!-- start card --><div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editare informatii cotatie</h3>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('quotes.update', $quote) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="price">Estimare Pret total</label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="20.000" value="{{ $quote->price ?? old('price') }}">
                            @error('price')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Descrieti cotatia dvs</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="8">{{ $quote->message ?? old('message') }}</textarea>
                            @error('message')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>
    
                        <div class="row">
                            <div class="col-lg-7">
                                <button type="submit" class="btn btn-primary"><i class="ti-pencil-alt"></i> Editeaza cotatia de pret</button>
                            </div>

                            <div class="col-lg-4">
                                <a href="{{ route('demands.show', $quote->demand_id) }}" class="btn btn-default float-right"><i class="ti-arrow-left"></i> Renunta</a>
                            </div>
                        </div>
                        
                    </form>
                    

                    

                </div>
            </div><!-- end card -->

        </div>
    </div>
    <!-- ROW-1 CLOSED -->

					</div>
				</div>
				<!--CONTAINER CLOSED -->
			</div>
@endsection
@section('js')
@endsection

@section('footer-scripts')


@endsection