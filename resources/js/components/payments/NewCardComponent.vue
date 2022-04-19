<template>
<div>
    <div class="row d-flex justify-content-center my-4">
        
        <div class="col-lg-8 my-4">
            <div class="disableClick" v-if="showSpinner">
                <moon-loader size="24px" color="blue" class="moonLoader"></moon-loader>
                <p class="text-loader">Se proceseaza...</p>
            </div>

            <card 
                class='stripe-card'
                ref="credit_card"
                :class='{ complete }'
                :stripe= "stripe_key"
                :options='stripeOptions'
                @change='changeField($event)'
            />
            <!-- We'll put the error messages in this element -->
            <div id="card-errors" role="alert" v-text="displayError"></div>
        </div>
        <br>
        <div class="col-lg-8 my-4">
            <button class='pay-with-stripe btn btn-success d-inline-flex' @click='pay' :disabled='!complete'>
                <span style="font-size:14px;" v-if="!showSpinner">Plateste</span>
                <template v-else>
                    <span style="font-size:14px;">Se inregistreaza plata</span>
                    <moon-loader size="20px" color="blue" class="m-1"></moon-loader>
                </template>
            </button>
      
            <div class="material-switch pull-right">
                <input id="remember_card_later" name="remember_card_later" type="checkbox" @change="remember_card_later($event)" ref="remember_card_later" />
                <label for="remember_card_later" class="label-success"></label>
            </div>
        </div>
    </div>
</div>
</template>

<script>

import EventCreditAmount from "./EventCreditAmount";

import { Card, card, createToken, createPaymentMethod, handleCardSetup } from 'vue-stripe-elements-plus';

import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

export default {
    name: "NewCardComponent",

    data(){
        return {
            // stripe: null,
            complete: false,
            card_error: true,
            displayError: '',
            save_card: false,
            credit_amount: null,
            response_success: null,
            response_status: false,
            stripeOptions: {
                // see https://stripe.com/docs/stripe.js#element-options for details
                style: {
                    base: {
                        color: "#32325d",
                    }
                },
                hidePostalCode: true
            },

            showSpinner: false
        }
    },

    props: {
        client_secret: String,
        the_credit_amount: String,
        credit_amount_status: Boolean,
        stripe_key: String
    },

    components: { 
        Card,
        MoonLoader
    },

    computed: {
        getCreditAmount: function(){
            return this.the_credit_amount;
        }
    },


    methods: {


        get_card(){
            // var res = elements.getElement('card');
            console.log(CardElement);
        },


        pay () {
            this.complete = false;
            this.showSpinner = true;
            self = this;
    

            createPaymentMethod(
                'card',
                {
                    billing_details: {
                        email: window.current_user.email,
                        name: window.current_user.last_name + ' ' + window.current_user.first_name
                    }
                }
            ).then(response => {
                        console.log(response);
                        const { paymentMethod, error } = response;
                        console.log(paymentMethod);
                        // this.resetFields();

                        if(error){
                            console.log(error);
                            this.displayError = error;
                             this.showSpinner = false;
                        } else {
                            console.log('CONTINUAM');
                            console.log('Payment method id: ' + paymentMethod.id);
                            console.log(paymentMethod);

                            // save card for laster use
                            if(this.save_card){
                                this.saveCard(paymentMethod);
                            }

                            // axios.get('http://127.0.0.1:8000/mime').then(response => {console.log(response)});

                            axios
                                .post('/payments/single/checkout', {
                                    payload: paymentMethod,
                                    paymentMethod: paymentMethod.id,
                                    amount: parseInt(this.getCreditAmount)
                                    // amount: 100
                                }, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    }
                                })
                                .then(response => {

                                    this.response_status = true;
                                    this.response_success = response.data.success;
                                    this.$emit('success_payment', {
                                        status: this.response_status,
                                        message: this.response_success
                                    });
                                    this.resetFields();
                                    self.showSpinner = false;
                                })
                                .catch(error => {
                                    // this.displayError = error.response.message;
                                    this.displayError = error.response.data.message;
                                });
                        }




                    }).catch(error => {
                        this.showSpinner = false;
                        console.log(error);
                    });

        },


        saveCard(payment_method){
            console.log('se salveaza card');
            // console.log(payment_method);

            // console.log(this.client_secret);
            // console.log(this.stripe);
            // console.log(this.Stripe);
            // console.log(payment_method.card);
            // console.log(Card.components);
            // console.log(card);
            // console.log(payment_method);

            // const local_stripe = Stripe("pk_test_51Gswe0I0uyZGzh5L1Q2KywiXFcIyfmimlLRgMnIwZoKSi92GY4o9av4udqO11D1cjGJb1FcbsABg7Jaql8YODfnN00juWgdo7w");
            // const elements = local_stripe.elements();
            // var cardElement = elements.getElement('credit_card');
            // var cardNumber = elements.getElement('cardNumber');
            // console.log(Card);
            // console.log(card);

            handleCardSetup(this.client_secret, card
            ).then(result => {
                //add card to existing methods. use axios to get existing methods. simulating refresh.
                this.$emit('save-card');
                console.log(result);
            }).catch(error => console.error(error));

        },

        remember_card_later: function(event){
            if(event.target.checked == true){
                this.save_card = true;
            } else {
                this.save_card = false;
            }
        },

        changeField(event){
            console.log(event.complete);
            if (event.error) {
                this.displayError = event.error.message;
            } else {
                this.displayError = '';
            }

            if(this.credit_amount_status == true){
                this.complete = event.complete;
            }
            this.card_error = false;
        },

        resetFields(){
            // console.log(this.$refs.credit_card);
            // console.log(this.credit_amount_status);
            // this.complete = false;
            this.displayError = '';
            this.credit_amount = null;
            this.card_error = true;
            this.$refs.credit_card.clear();
        },

    },


    created(){

        console.log("stripe_key is");
        console.log(this.stripe_key);


        const self = this;
        EventCreditAmount.$on('credit_amount_status_changed', function(value){
            console.log('testam ' + value)
            if(value == true && self.card_error == false){
                self.complete = true;
            } else {
                self.complete = false;
            }
        });

        // this.stripe = Stripe("pk_test_51Gswe0I0uyZGzh5L1Q2KywiXFcIyfmimlLRgMnIwZoKSi92GY4o9av4udqO11D1cjGJb1FcbsABg7Jaql8YODfnN00juWgdo7w");
        //     // console.log(stripe);
        
        //     const elements = this.stripe.elements();

        //     var style = {
        //             base: {
        //                 color: "#32325d",
        //             }
        //         };
        

        //     this.cardElement = elements.create('card', {
        //         style: style,
        //         hidePostalCode: true,
        //         authentication_required: true
        //     });

        //     this.cardElement.mount('#card-element');

        //     // errros on typing card details
        //     this.cardElement.on('change', ({error}) => {
        //         let displayError = document.getElementById('card-errors');
        //         if (error) {
        //             displayError.textContent = error.message;
        //         } else {
        //             displayError.textContent = '';
        //         }
        //     });


    }

}
</script>

<style scoped>
.stripe-card {
  width: 80%;
  border: none;
}
.stripe-card.complete {
  border-color: green;
}

#card-errors {
    color: red;
}



div.disableClick {
    width: 95%;
    display: block;
    height: 94%;
    background: #e9e9e9;
    z-index: 2;
    position: absolute;
    opacity: 0.5;
}


.moonLoader {
    height: 24px;
    width: 24px;
    border-radius: 100%;
    margin: 0 auto;
    margin-top: -10px;
    /* margin-top: 20%; */
}

.text-loader {
    text-align: center;
}
</style>