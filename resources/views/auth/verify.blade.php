{{-- @extends('volgh.layouts.master-normal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">{{ __('Verificati adresa de e-mail.') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('V-am trimis un link de verificare pe e-mail. Va rugam sa verificati casuta de e-mail si sa validati contul.') }}
                        </div>
                    @endif

                    {{ __('Inainte de a continua, va rugam sa verificati casuta de e-mail. Cautati mesajul nostru care contine link-ul de verificare al contului. Va rugam sa verificati si folderul Spam.') }}
                    {{ __('Daca nu ati primit mesajul nostru de verificare, retrimiteti acest email apasand pe butonul de mai jos') }}.
                    <br>
                    <form class="d-inline mt-10" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ __('Retrimite email de verificare') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}





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
</style>
@endsection

@section('title-page')
<title>Verificare adresa email REFORMEX</title>
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
						
							<div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header"><strong>{{ __('Verific?? adresa de e-mail.') }}</strong></div>
                        
                                        <div class="card-body">
                                            @if (session('resent'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ __('Un link de verificare a fost trimis pe e-mail. Verific?? c??su??a de email ??i valideaz?? contul.') }}
                                                </div>
                                            @endif
                        
                                            {{ __('Verific?? adresa de e-mail. Trimite mesajul cu link-ul de confirmare ap??s??nd butonul de mai jos. Caut?? mesajul nostru cu link-ul de verificare al contului. Dac?? nu e ??n inbox, caut?? ??i ??n spam.') }}
                                            {{ __('??n cazul ??n care mesajul de verificare e de neg??sit, retrimite e-mailul prin ap??sarea butonului de mai jos.') }}.
                                            <br>
                                            <form class="mt-2" method="POST" action="{{ route('verification.resend') }}" style="margin: 0 auto; display: block; text-align: center;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">{{ __('Trimite e-mail de verificare') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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