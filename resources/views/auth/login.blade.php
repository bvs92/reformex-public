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
</style>
@endsection

@section('title-page')
<title>Autentificare REFORMEX</title>
@endsection

@section('content')
		<!-- BACKGROUND-IMAGE -->
		<div class="login-img" id="background-screen">

			<!-- GLOABAL LOADER -->
			<div id="global-loader">
				{{-- <img src="{{URL::asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader"> --}}
                <img src="{{URL::asset('assets/images/ref.gif')}}" class="loader-img" alt="Loader" style="top: 23%!important;">   
			</div>
			<!-- /GLOABAL LOADER -->

			<!-- PAGE -->
			<div class="page">
				<div class="">
				    <!-- CONTAINER OPEN -->
					<div class="col col-login mx-auto">
						<div class="text-center">
							<a href="/"><img src="{{URL::asset('assets/images/brand/reformex-logo-black.png')}}" class="header-brand-img" alt="reformex" style="height:5rem;"></a>
						</div>
					</div>
					<div class="container-login100">
						<div class="wrap-login100 p-6">
							<form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
								<span class="login100-form-title">
									Autentificare
                                </span>
                                    @csrf
            
                                    <div class="wrap-input100 validate-input">
                                        <input id="email" type="email" class="input100 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Adresă de e-mail">
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


                                    <div class="wrap-input100 validate-input">
                                        <input id="password" type="password" class="input100 form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Parolă">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                        </span>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="wrap-input100 d-flex justify-content-end">
                                        <label class="form-check-label px-4" for="remember">
                                            Ține-mă minte
                                        </label>
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    </div>

                                    <div class="container-login100-form-btn">
                                        <button type="submit" class="login100-form-btn btn-turcoaz">
                                            Autentificare
                                        </button>
                                    </div>


                                    <div class="text-right pt-1">
                                        @if (Route::has('password.request'))
                                            <p class="mb-0">
                                                <a class="text-primary ml-1" href="{{ route('password.request') }}">
                                                    Ai uitat parola?
                                                </a>
                                            </p>
                                        @endif
                                        
                                    </div>
                             

								<div class="text-center pt-3">
                                <p class="text-dark mb-0">Nu ai încă cont?<a href="https://www.reformex.ro/inscriere-profesionist/" class="text-primary ml-1">Înregistrează-te</a></p>
								</div>
								{{-- <div class=" flex-c-m text-center mt-3">
								    <p>Sau</p>
									<div class="social-icons">
										<ul>
											<li><a class="btn  btn-social btn-block"><i class="fa fa-google-plus text-google-plus"></i> Autentificare cu Google</a></li>
											<li><a class="btn  btn-social btn-block mt-2"><i class="fa fa-facebook text-facebook"></i> Autentificare cu Facebook</a></li>
										</ul>
									</div>
								</div> --}}
							</form>
						</div>
					</div>
					<!-- CONTAINER CLOSED -->
				</div>
			</div>
			<!-- End PAGE -->

		</div>
		<!-- BACKGROUND-IMAGE CLOSED -->
@endsection
@section('js')
@endsection