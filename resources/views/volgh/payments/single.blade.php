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
                                    <div class="form-group">
                                        <input id="credit_amount" name="credit_amount" type="numeric" class="form-control text-center bg-white" placeholder="Suma in lei" style="font-size:32px;height:80px;" />
                                    </div>
                                </div>

                                <div class="col-lg-6 offset-lg-2">
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
                                </div>

                                <div class="clearfix my-4"></div>

                                <div class="col-lg-6 mt-6">
                                    <h3>Adauga card nou</h3>
                                    <div>

                                    <div class="form-group">
                                        <label for="card-holder-name">Numele de pe card</label>
                                        <input id="card-holder-name" name="card-holder-name" type="text" class="form-control">
                                    </div>
            
                                    <!-- Stripe Elements Placeholder -->
                                    <div class="form-group my-4">
                                        <label for="card-element">Datele cardului</label>
                                        <div id="card-element" class="my-2"></div>
                                        <div id="errors_container"></div>
                                    </div>

                                    {{-- <div class="form-group">
                                        <label for="credit_amount">Suma (in RON)</label>
                                        <input id="credit_amount" name="credit_amount" type="numeric" class="form-control">
                                    </div>
             --}}


                                        <button id="card-button" class="btn btn-primary my-2">
                                            Plateste cu acest card
                                        </button>

                                        <div class="material-switch pull-right">
                                            <input id="save-credit-card" name="save-credit-card" type="checkbox"/>
                                            <label for="save-credit-card" class="label-success"></label>
                                        </div>

                                    </div>


                                    <hr>

                                    <!-- placeholder for Elements -->
                                    {{-- <form id="setup-form" 
                                    data-secret="<?= $intent->client_secret ?>"
                                    >
                                        <div id="card-element-save"></div>
                                        <button id="card-button-save">
                                            Save Card
                                        </button>
                                    </form> --}}

                                    <div id="messages"></div>

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


            const $messages = document.getElementById('messages');


            $saveCreditCard = document.getElementById('save-credit-card');

            // $saveCreditCard.addEventListener('change', function(e){
            //     // if($saveCreditCard.checked)
            //         console.log($saveCreditCard.checked);
            // });



            // For saving the card (OLD)
            // var cardButtonSave = document.getElementById('card-button-save');
            // var clientSecret = document.getElementById('setup-form').dataset.secret;

            // console.log('Client secret: ' + clientSecret);

            // cardButtonSave.addEventListener('click', function(ev) {
            //     ev.preventDefault();

            //     stripe.confirmCardSetup(
            //         clientSecret,
            //         {
            //             payment_method: {
            //                 card: cardElement
            //             },
            //         }
            //     ).then(function(result) {
            //         if (result.error) {
            //             // Display error.message in your UI.
            //             console.log('Error saving:');
            //             console.error(result.error);
            //         } else {
            //             // The setup has succeeded. Display a success message.
            //             console.log('Saved..');
            //         }
            //     });
            // });

            // end for saving the card


           
            // for add credit

            cardButton.addEventListener('click', async (e) => {

                // const { paymentMethod, error } = await stripe.createPaymentMethod(
                //     'card', cardElement, {
                //         billing_details: { name: cardHolderName.value },
                //     }
                // );

                const $testPmnt = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: { name: cardHolderName.value },
                    }
                );

                const { paymentMethod, error } = $testPmnt;

                // const plan = document.getElementById('plan').value;

                // console.log('plan');
                console.log($testPmnt);

                // console.log(error);
                // console.log(paymentMethod);



                if($saveCreditCard.checked){
                    // var cardButtonSave = document.getElementById('card-button-save');
                    // var clientSecret = document.getElementById('setup-form').dataset.secret;
                    var clientSecret = '{{ $intent->client_secret }}';

                    // console.log('Client secret: ' + clientSecret);

                        stripe.confirmCardSetup(
                            clientSecret,
                            {
                                payment_method: {
                                    card: cardElement
                                },
                            }
                        ).then(function(result) {
                            if (result.error) {
                                // Display error.message in your UI.
                                console.log('Error saving:');
                                console.error(result.error);
                            } else {
                                // The setup has succeeded. Display a success message.
                                console.log('Saved..');
                            }
                        });
                } // end $saveCreditCard.checked



                const creditAmount = document.getElementById('credit_amount').value;

                if (error) {
                    // Display "error.message" to the user...
                    console.log(error.message);


                    // var dismissAlert = document.createElement('DIV');
                    // dismissAlert.classList.add('alert alert-warning alert-dismissible fade show');
                    // dismissAlert.setAttribute('role', 'alert');

                    // var btnAlert = document.createElement('BUTTON');
                    // btnAlert.classList.add('close');
                    // btnAlert.setAttribute('data-dismiss', 'alert');
                    // btnAlert.setAttribute('aria-label', 'Close');

                    // var span

                    var paragraph = document.createElement("P");
                    paragraph.innerText = error.message;
                    paragraph.classList.add('text-danger');
                    console.log(paragraph);
                    var errors_container = document.getElementById('errors_container');
                    errors_container.innerHTML = "";
                    errors_container.appendChild(paragraph);

                    
                } else {
                    // The card has been verified successfully...
                    console.log('Payment method details');
                    console.log('Credit: ' + creditAmount);
                    console.log('Payment method id: ' + paymentMethod.id);
                    console.log(paymentMethod);

                    // axios.get('http://127.0.0.1:8000/mime').then(response => {console.log(response)});

                    axios
                        .post('http://127.0.0.1:8000/payments/single/checkout', {
                            payload: paymentMethod,
                            paymentMethod: paymentMethod.id,
                            amount: creditAmount
                        }, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => {
                            console.log("aici");
                            console.log(response.data);

                            var alertDiv = document.createElement("div");
                            alertDiv.setAttribute('class', 'alert alert-success');
                            alertDiv.setAttribute('role', 'alert');

                            var btnAlert = document.createElement("button");
                            btnAlert.setAttribute('class', 'close');
                            btnAlert.setAttribute('data-dismiss', 'alert');
                            btnAlert.setAttribute('aria-hidden', 'true');
                            btnAlert.setAttribute('type', 'button');
                            btnAlert.innerText = 'x';

                            alertDiv.appendChild(btnAlert);
                            alertDiv.innerText = response.data.success;

                            $messages.appendChild(alertDiv);




                            // location.reload();
                        })
                        .catch(error => {
                            console.error(error);
                            var paragraph = document.createElement("P");
                            paragraph.innerText = error.message;
                            paragraph.classList.add('text-danger');
                            console.log(paragraph);
                            var errors_container = document.getElementById('errors_container');
                            errors_container.innerHTML = "";
                            errors_container.appendChild(paragraph);

                        });
                }
            });





            /// later saved card (DELETE?)
            laterCardButton.addEventListener('click', async (e) => {

                const { paymentMethod, error } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: { name: cardHolderName.value },
                    }
                );

                // const plan = document.getElementById('plan').value;

                // console.log('plan');
                // console.log(plan);

                // console.log(error);
                // console.log(paymentMethod);

                const creditAmount = document.getElementById('credit_amount').value;

                if (error) {
                    // Display "error.message" to the user...
                    console.log(error.message);


                    // var dismissAlert = document.createElement('DIV');
                    // dismissAlert.classList.add('alert alert-warning alert-dismissible fade show');
                    // dismissAlert.setAttribute('role', 'alert');

                    // var btnAlert = document.createElement('BUTTON');
                    // btnAlert.classList.add('close');
                    // btnAlert.setAttribute('data-dismiss', 'alert');
                    // btnAlert.setAttribute('aria-label', 'Close');

                    // var span

                    var paragraph = document.createElement("P");
                    paragraph.innerText = error.message;
                    paragraph.classList.add('text-danger');
                    console.log(paragraph);
                    var errors_container = document.getElementById('errors_container');
                    errors_container.innerHTML = "";
                    errors_container.appendChild(paragraph);

                    
                } else {
                    // The card has been verified successfully...
                    console.log('Payment method details');
                    console.log('Credit: ' + creditAmount);
                    console.log('Payment method id: ' + paymentMethod.id);
                    console.log(paymentMethod);

                    // axios.get('http://127.0.0.1:8000/mime').then(response => {console.log(response)});

                    axios
                        .post('http://127.0.0.1:8000/payments/single/checkout', {
                            payload: paymentMethod,
                            paymentMethod: paymentMethod.id,
                            amount: creditAmount
                        }, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => {
                            console.log("aici");
                            // console.log(response.data);
                            // location.reload();
                        })
                        .catch(error => {
                            console.error(err);
                            var paragraph = document.createElement("P");
                            paragraph.innerText = error.message;
                            paragraph.classList.add('text-danger');
                            console.log(paragraph);
                            var errors_container = document.getElementById('errors_container');
                            errors_container.innerHTML = "";
                            errors_container.appendChild(paragraph);

                        });
                }
                });


            // end later saved card


            // }, {once: true});
        });
</script>
@endsection
			
	
	

		