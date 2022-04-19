@extends('volgh.layouts.master')
@section('css')

<script src="https://js.stripe.com/v3/"></script>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>


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
                            <div class="row justify-content-around">
                                <div class="col-lg-6">
                                    <div>

                                        <h3>Carduri existente</h3>
                                        @if($existing_methods->count() > 0)
                                        <ul class="list-group">
                                            @foreach($existing_methods as $method)
											<li class="list-group-item" style="font-size:16px;">
												{{ str_pad($method->card->last4, 14, '*', STR_PAD_LEFT) }} <span class="mx-2">(<strong>{{ucfirst($method->card->brand) }}</strong>)</span> <span class="text-muted mx-2">{{ $method->card->exp_month }}/{{ $method->card->exp_year }}</span>
												<div class="material-switch pull-right">
													<input id="{{ $method->id }}" name="{{ $method->id }}" type="checkbox"/>
													<label for="{{ $method->id }}" class="label-success"></label>
												</div>
											</li>
                                            @endforeach
                                        </ul>
                                        @endif

                                        <br><br>
                                        <h3>Adauga card nou</h3>

                                    <div class="form-group">
                                        <input id="card-holder-name" name="card-holder-name" type="text" class="form-control bg-white" placeholder="Numele de pe card">
                                    </div>
            
                                    <!-- Stripe Elements Placeholder -->
                                    <div class="form-group my-4">
                                        <label for="card-element">Datele cardului</label>
                                        <div id="card-element" class="my-2"></div>
                                        <div id="errors_container"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group my-2">
                                                <input type="numeric" class="form-control bg-white text-center" name="credit_amount" placeholder="Suma in lei">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group my-2">
                                                <button class="btn btn-primary btn-block" id="submit-button">Plateste</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div id="card-errors" class="alert" role="alert"></div>
                                    </div>
{{-- 
                                    <div class="form-group">
                                        <label for="credit_amount">Suma (in RON)</label>
                                        <input id="credit_amount" name="credit_amount" type="numeric" class="form-control">
                                    </div>
            
                                    <button id="card-button" class="btn btn-primary my-2">
                                        Plateste cu acest card
                                    </button> --}}
                                    </div>


                                    <hr>

                                    <!-- placeholder for Elements -->
                                    {{-- <form id="setup-form" data-secret="<?= $intent->client_secret ?>">
                                        <div id="card-element-save"></div>
                                        <button id="card-button-save">
                                            Save Card
                                        </button>
                                    </form> --}}

                                </div>
                            </div>
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

<script>

    
    window.addEventListener('load', function(){
            const stripe = Stripe("{{ config('services.stripe.stripe_key') }}");
            // console.log(stripe);
        
            const elements = stripe.elements();
            const cardElement = elements.create('card');
        
            cardElement.mount('#card-element');

            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');

            const laterCardButton = document.getElementById('later-card-button');
            const theSubmitButton = document.getElementById('submit-button');


            cardElement.on('change', function(event) {
                var displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                }
            );


                const _clientSecretNew = '{{ $intent->client_secret }}';

                console.log("Client secret from SERVER: " + _clientSecretNew);




                


            // stripe.confirmCardPayment(intent.client_secret, {
            // payment_method: intent.last_payment_error.payment_method.id
            // }).then(function(result) {
            // if (result.error) {
            //     // Show error to your customer
            //     console.log(result.error.message);
            // } else {
            //     if (result.paymentIntent.status === 'succeeded') {
            //     // The payment is complete!
            //     console.log('payment is completed');
            //     }
            // }
            // });


           
            // for add credit

            theSubmitButton.addEventListener('click', async (e) => {


                stripe.confirmCardPayment(_clientSecretNew, {
                    payment_method: {
                        card: cardElement,
                    },
                    setup_future_usage: 'off_session'
                }).then(function(result) {
                    console.log(result);
                    if (result.error) {
                        // Show error to your customer
                        console.error('Suntem la primul fail');
                        console.error(result.error.message);

                        var displayError = document.getElementById('card-errors');
                        displayError.textContent = result.error.message;

                        // if(result.error) {
                        //     displayError.textContent = result.error.message;
                        // } else {
                        //     displayError.textContent = '';
                        // }
                        
                    } else {
                            if(result.paymentIntent.status === 'succeeded') {
                                console.log('MERGE ASTA?');
                            // Show a success message to your customer
                            // There's a risk of the customer closing the window before callback execution
                            // Set up a webhook or plugin to listen for the payment_intent.succeeded event
                            // to save the card to a Customer

                            // The PaymentMethod ID can be found on result.paymentIntent.payment_method

                            // axios
                            //     .post('http://127.0.0.1:8000/payments/single-later/checkout', {
                            //         paymentMethodId: result.paymentIntent.payment_method
                            //     },{
                            //         headers: {
                            //             'X-Requested-With': 'XMLHttpRequest',
                            //             'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            //             'Accept': 'application/json',
                            //             'Content-Type': 'application/json',
                            //         }
                            //     }).then(response => {
                            //         console.log("aici");
                            //         // console.log(response.data);
                            //         // location.reload();
                            //     }).catch(error => {
                            //         console.error(err);
                            //     });

                                // end axios

                            }
                }});

            });



            // }, {once: true});
        });
</script>
@endsection
			
	
	

		