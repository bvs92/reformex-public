<template>
    <div>
        <div class="row d-flex justify-content-center my-4">
            <div class="col-lg-8">
                <div class="form-group">
                    <validation-provider
                        ref="credit_amount"
                        rules="required|integer|min_value:20"
                        v-slot="{ errors, valid }"
                    >
                        <input
                            id="credit_amount"
                            @change="isValid(valid)"
                            @focusout="focusOut(valid)"
                            name="credit_amount"
                            v-model="credit_amount"
                            class="form-control text-center bg-white"
                            placeholder="Suma in lei"
                            style="font-size:32px;height:80px;"
                        />
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="tab_wrapper first_tab col-lg-8">
                <ul class="tab_list">
                    <li class="active">Carduri existente</li>
                    <li>Card nou</li>
                </ul>

                <div class="content_wrapper">
                    <div class="tab_content active" v-if="existing_methods">
                        <existing-cards-component
                            :existing_methods="get_existing_methods"
                            :the_credit_amount="credit_amount"
                            @success_payment="success_payment"
                        ></existing-cards-component>
                    </div>

                    <div class="tab_content">
                        <new-card-component
                            @success_payment="success_payment"
                            :stripe_key="the_stripe_key"
                            :credit_amount_status="credit_amount_status"
                            :client_secret="client_secret"
                            :the_credit_amount="credit_amount"
                            @save-card="card_saved"
                        ></new-card-component>
                    </div>
                </div>
                <!-- end content_wrapper -->
            </div>
        </div>
        <!-- end row -->

        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                    v-if="response_status"
                >
                    <p class="text-center">{{ response_success }}</p>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                        @click="closeAlert()"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EventCreditAmount from "./EventCreditAmount";

import ExistingCardsComponent from "./ExistingCardsComponent";
import NewCardComponent from "./NewCardComponent";
import {
    Card,
    createToken,
    createPaymentMethod
} from "vue-stripe-elements-plus";

import { ValidationProvider, extend } from "vee-validate";
import { required, integer, min_value } from "vee-validate/dist/rules";

extend("required", {
    ...required,
    message: "Acest camp este obligatoriu."
});

extend("integer", {
    ...integer,
    message: "Sunt acceptate doar valori numerice intregi."
});

extend("min_value", {
    ...min_value,
    message: "Valoarea minima acceptata este 20."
});

export default {
    name: "CardPaymentComponent",
    props: {
        _existing_methods: Array,
        client_secret: String,
        the_stripe_key: String
    },

    data() {
        return {
            credit_amount: null,
            response_success: null,
            response_status: false,
            existing_methods: [],
            credit_amount_status: false
        };
    },

    components: {
        Card,
        "existing-cards-component": ExistingCardsComponent,
        "new-card-component": NewCardComponent,

        ValidationProvider
    },

    computed: {
        get_existing_methods: function() {
            return this.existing_methods;
        }
    },

    methods: {
        save_data(new_data) {
            this.existing_methods = new_data;
        },
        closeAlert() {
            this.response_status = false;
            this.response_success = null;
        },

        isValid(value) {
            this.credit_amount_status = value;
        },

        focusOut(value) {
            console.log(value);
            EventCreditAmount.$emit("credit_amount_status_changed", value);
        },

        success_payment: function(element) {
            this.response_status = element.status;
            this.response_success = element.message;
            this.credit_amount = null;
            this.credit_amount_status = false;
            this.$refs.credit_amount.reset(); // reset errors.
            // this.$validator.clean(); ?? also for errors

            let self = this;
            // clear alert after 1 min
            setTimeout(function() {
                self.closeAlert();
            }, 60000);
        },

        card_saved: function() {
            axios.get("/api/payments/existing/cards").then(response => {
                // console.log('card saved here.');
                // console.log(response);
                // this.save_data(response.data.existing_cards);

                // this.$nextTick(function () {
                //     console.log(this.$el.textContent) // => 'updated'
                // })

                this.existing_methods = [...response.data.existing_cards];
                // this.$forceUpdate()
                // this.$set('existing_methods', response.data.existing_cards)
            });
        }
    },

    created() {
        this.existing_methods = this._existing_methods;
    }
};
</script>

<style scoped></style>
