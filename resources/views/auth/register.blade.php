@extends('volgh.layouts.master2')
@section('css')
<link href="{{ URL::asset('assets/plugins/single-page/css/main.css')}}" rel="stylesheet">

<style>
    #background-screen {
        background-image: url("{{URL::asset('assets/images/background/background-register.jpg')}}");
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
							<img src="{{URL::asset('assets/images/brand/reformex-logo-black.png')}}" class="header-brand-img" alt="reformex" style="height:5rem;">
						</div>
					</div>
					<div class="container-login100">
						<div class="wrap-login100 p-6">
                            <form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
                                @csrf
								<span class="login100-form-title">
									Creare cont nou
                                </span>
                                

                                <div class="wrap-input100 validate-input">
                                    <input id="last_name" type="text" class="input100 form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name"  placeholder="Nume">
									{{-- <span class="focus-input100"></span> --}}
									<span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
                                    </span>
                                    
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


								<div class="wrap-input100 validate-input">
                                    <input id="first_name" type="text" class="input100 form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name"  placeholder="Prenume">
									{{-- <span class="focus-input100"></span> --}}
									<span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
                                    </span>
                                    
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                
                                <div class="wrap-input100 validate-input">
                                    <input id="email" type="email" class="input100 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="Adresa de e-mail">
                                    {{-- <span class="focus-input100"></span> --}}
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
                                    <input id="password" type="password" class="input100 form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Parola">
                                    {{-- <span class="focus-input100"></span> --}}
                                    <span class="symbol-input100">
                                        <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                    </span>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


								<div class="wrap-input100 validate-input">
                                    <input id="password-confirm" type="password" class="input100 form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirma parola">
                                    {{-- <span class="focus-input100"></span> --}}
                                    <span class="symbol-input100">
                                        <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                    </span>

                                    @error('password-confirm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                


								<label class="custom-control custom-checkbox mt-4">
									<input type="checkbox" class="custom-control-input" id="terms_cond">
									<span class="custom-control-label">Sunt de acord cu <a href="terms.html">termenii si conditiile</a></span>
								</label>
								<div class="container-login100-form-btn">
									<button type="submit" class="login100-form-btn btn-turcoaz" id="register_btn" disabled="disabled">
										Inregistrare
                                    </button>
								</div>
								<div class="text-center pt-3">
									<p class="text-dark mb-0">Ai deja un cont pe Reformex?<a href="{{ route('login') }}" class="text-primary ml-1">Autentificare aici</a></p>
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
			<!-- END PAGE -->

		</div>
		<!-- BACKGROUND-IMAGE CLOSED -->
@endsection
@section('js')

<script>
    $(document).ready(function(){
        $terms = $('#terms_cond');
        $register_btn = $('#register_btn');

        $terms.click(function(){
            if($(this).is(':checked'))
                $register_btn.prop('disabled', false);
            else
            $register_btn.prop('disabled', true);
        });
    });
</script>
@endsection