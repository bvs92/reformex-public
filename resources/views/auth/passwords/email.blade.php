@extends('volgh.layouts.master2')
@section('css')
<link href="{{ URL::asset('assets/plugins/single-page/css/main.css')}}" rel="stylesheet">

<style>
    #background-screen {
        background-image: url("{{URL::asset('assets/images/background/background-login.jpg')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-color: #cccccc;
        background-size: cover;
    }

    .btn-primary.disabled, .btn-primary:disabled {
       background-color: #8b989f!important; 
       cursor: not-allowed;
    }
</style>

@endsection


@section('title-page')
<title>Resetare parola REFORMEX</title>
@endsection


@section('content')
	    <!-- BACKGROUND-IMAGE -->
		<div class="login-img" id="background-screen">
			
			<!-- GLOABAL LOADER -->
			<div id="global-loader">
				{{-- <img src="{{URL::asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader"> --}}
				<img src="{{URL::asset('assets/images/ref.gif')}}" class="loader-img" alt="Loader" style="top: 23%!important;">   
			</div>
			<!-- End GLOABAL LOADER -->

			<!-- PAGE -->
			<div class="page">
				<div class="">
				    <div class="col col-login mx-auto">
						<div class="text-center">
							<a href="/"><img src="{{URL::asset('assets/images/brand/reformex-logo-black.png')}}" class="header-brand-img" alt="reformex" style="height:5rem;"></a>
						</div>
					</div>
				    <!-- CONTAINER OPEN -->
					<div class="container-login100">
						<div class="row">
							<div class="col col-login mx-auto">
                                <form class="card shadow-none" method="POST" action="{{ route('password.email') }}">
                                    @csrf
									<div class="card-body p-6">
                                        <h3 class="text-center card-title">Ai uitat parola?</h3>
                                            
                                            <div class="wrap-input100 validate-input">
                                                <input id="email" type="email" class="input100 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Adres?? de e-mail">
                                                <span class="focus-input100"></span>
                                                <span class="symbol-input100">
                                                    <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                                </span>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>


											<div class="form-footer">
												<button type="submit" class="btn btn-turcoaz btn-block">Reseteaz?? parola</button>
											</div>
										<div class="text-center text-muted mt-3 ">
										??ntoarce-te la pagina de <a href="{{ route('login') }}">autentificare</a>.
										</div>

										@if(session('status'))
                                        <div class="text-center text-muted mt-3 ">
                                            Vei primi un e-mail cu detaliile de resetare a parolei. Verific?? casu??a de e-mail (Inbox si Spam).
                                        </div>
										@endif
										

									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- CONTAINER CLOSED -->
				</div>
			</div>
			<!--END PAGE -->

		</div>
		<!-- BACKGROUND-IMAGE CLOSED -->

@endsection
@section('js')
@endsection