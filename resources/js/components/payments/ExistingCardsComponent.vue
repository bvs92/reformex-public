<template>
    <div class="row d-flex justify-content-center">
        <div class="col-lg-8 d-inline-flex">
            <div class="col-lg-10 pull-left">
                <h3>Carduri existente</h3>
            </div>
            <div class="col-lg-2 pull-right">
                <button class="btn btn-sm btn-default" @click="refresh()">Reincarca</button>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-8"><p>Detalii card</p></div>
                <div class="col-lg-4"><p class="pull-right">Selecteaza card</p></div>
            </div>
            <!-- <div class="disableClick" v-if="showSpinner"></div> -->
            <div class="disableClick" style="height: 100%;" v-if="showSpinner">
                <moon-loader size="24px" color="blue" class="moonLoader"></moon-loader>
                <p class="text-loader">Se proceseaza...</p>
            </div>
            <ul class="list-group payment_methods_list" v-if="payment_methods">
                
                <li v-for="method in payment_methods" :key="method.id" class="list-group-item" style="font-size:16px;">
                    {{ full_card_number(method.card.last4) }} <span class="mx-2">(<strong>{{ method.card.brand }}</strong>)</span> <span class="text-muted mx-2">{{ method.card.exp_month }}/{{ method.card.exp_year }}</span>
                    <div class="material-switch pull-right">
                        <input :id="method.id" :name="method.id" :value="method.id" type="checkbox" @change="check($event)" ref="list_checkbox" />
                        <label :for="method.id" class="label-success"></label>
                    </div>
                </li>
                
            </ul>
            <!-- <p>Selected method: {{ selected_method }}</p> -->
        </div>
        <div class="col-lg-8 my-6">
            <button class='pay-with-stripe btn btn-success d-inline-flex' @click='pay' :disabled='!complete'>
                <span style="font-size:14px;" v-if="!showSpinner">Plateste</span>
                <template v-else>
                    <span style="font-size:14px;">Se inregistreaza plata</span>
                    <moon-loader size="20px" color="blue" class="m-1"></moon-loader>
                </template>
            </button>
        </div>
    </div>
</template>

<script>
import EventCreditAmount from "./EventCreditAmount";

import { createPaymentMethod } from 'vue-stripe-elements-plus';

import MoonLoader from 'vue-spinner/src/MoonLoader.vue';


export default {
    name: "ExistingCardsComponent",

    data(){
        return {
            complete: false,
            selected_method: null,
            payment_methods: [],

            showSpinner: false
        }
    },

    components: {
        MoonLoader
    },

    props: {
        existing_methods: Array,
        the_credit_amount: String
    },

    computed: {
        getCreditAmount: function(){
            return parseInt(this.the_credit_amount);
        }
    },

    methods: {
        check: function(event){
            self = this;
            console.log(event);
            console.log(event.target.checked);
            console.log(event.target.value);
            console.log(this.$refs.list_checkbox);

            // select only 1 method from checkboxes
            this.$refs.list_checkbox.forEach(function(item){
                if(event.target.checked == true){

                    if(item.value != event.target.value){
                        item.checked = false;
                    } else {
                        if(self.getCreditAmount > 20){
                            self.complete = true;
                        }
                        self.selected_method = self.payment_methods.find(method => method.id == event.target.value);
                    }

                } else if(event.target.checked == false){
                    self.complete = false;
                    self.selected_method = null;
                }
            });

            console.log("Selected method is: ");
            console.log(this.selected_method);

        },

        pay: function(){

            self = this;
            // reset 
            this.complete = false;
            this.showSpinner = true;


            // pay

            axios
                .post('/payments/single/checkout', {
                    payload: this.selected_method,
                    paymentMethod: this.selected_method.id,
                    amount: this.getCreditAmount
                    // amount: 10
                }, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    // this.response_status = true;
                    // this.response_success = response.data.success;
                    console.log(response.data.success);
                    // this.resetFields();

                    this.$emit('success_payment', {
                        status: true,
                        message: response.data.success
                    });
                    
                    this.selected_method = null;
                    this.$refs.list_checkbox.forEach(function(item){
                        item.checked = false;
                    });

                    this.showSpinner = false;


                })
                .catch(error => {
                    // this.displayError = error.response.data.message;
                    console.log(error.response.data.message);
                    this.showSpinner = false;
                });
        },


        refresh: function(){
            axios.get('/api/payments/existing/cards').then(response => {
                console.log('card saved here.');
                console.log(response);
                // this.save_data(response.data.existing_cards);

                // this.$nextTick(function () {
                //     console.log(this.$el.textContent) // => 'updated'
                // })

                this.payment_methods = [...response.data.existing_cards];
                // this.$forceUpdate()
                // this.$set('existing_methods', response.data.existing_cards)
            });
        },

        full_card_number: function(the_string){
            return the_string.padStart(16, '*');
        }
    },


    created(){
        console.log(this.existing_methods);

        this.payment_methods = this.existing_methods;


        const self = this;
        EventCreditAmount.$on('credit_amount_status_changed', function(value){
            console.log('testam ' + value)
            if(value == true && self.selected_method !== null){
                self.complete = true;
            } else {
                self.complete = false;
            }
        });
    }
}
</script>

<style scoped>

div.disableClick {
    width: 95%;
    display: block;
    height: 94%;
    background: #e9e9e9;
    z-index: 2;
    position: absolute;
    opacity: 0.5;
}

ul.payment_methods_list {
    z-index: 0;
}


.moonLoader {
    height: 24px;
    width: 24px;
    border-radius: 100%;
    margin: 0 auto;
    margin-top: 20%;
}

.text-loader {
    text-align: center;
}

</style>