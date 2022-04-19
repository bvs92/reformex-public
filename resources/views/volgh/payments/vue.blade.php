@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
<script src="https://js.stripe.com/v3/"></script>
<!-- Scripts -->
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Plati</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Plati</a></li>
                <li class="breadcrumb-item active" aria-current="page">Plata noua</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Alimentare cont</h3>
                        </div>

                        <div class="card-body">
                            <h2>Plati cu VUE si Stripe</h2>
                            <card-payment-component :_existing_methods="{{ json_encode($existing_methods->data) }}" :client_secret="{{ json_encode($intent->client_secret) }}" :the_stripe_key="{{ json_encode(config('services.stripe.stripe_key')) }}"></card-payment-component>
                        </div>

                    </div>
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>

@endsection
			
	
	

		