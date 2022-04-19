<template>
  <div>
      <a href="" id="add__new__coupon" class="btn btn-md btn-primary" @click.prevent="modalShow = !modalShow"><i class="fa fa-plus"></i> Adaugă cupon nou</a>
      <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă un nou cupon">
            <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min_value:10" v-slot="{ errors, invalid, passed, touched }">
                            <label for="amount_value">Valoare</label>
                            <input type="number" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="amount_value" 
                            placeholder="200" name="amount_value"
                            v-model="amount_value"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['amount_value']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['amount_value']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12 my-2">
                        <validation-provider rules="" v-slot="{ errors, invalid, passed, touched }">
                            <multiselect v-model="selected_user" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            :options="getUsers" 
                            :multiple="false" 
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :preserve-search="true" 
                            :hide-selected="true"
                            placeholder="Alege un utilizator" 
                            selectLabel=""
                            deselectLabel=""
                            :loading="loadingStatus"
                            selectedLabel="Selectat"
                            label="email" track-by="email" 
                            :preselect-first="false"
                            open-direction="bottom"
                            @select="selectItem" 
                            @remove="removeItem"
                            >

                                <template slot="selection" slot-scope="{ values, isOpen }">
                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Categorii selectate</span>
                                </template>

                                <span slot="noOptions">Lista este goală. Nu există nicio categorie.</span>
                                <span slot="noResult">Oops! Nu am găsit niciun element cu acest nume.</span>
                                <span slot="maxElements">Numărul maxim de elemente selectate a fost atins.</span>
                                <span slot="afterList" class="text-muted p-2"><small>Poți selecta una sau mai multe categorii.</small></span>

                            </multiselect>  
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <!-- <template v-if="validation_errors">
                            <template v-if="validation_errors['email']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['last_name']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template> -->
                    </div>

                    <div class="col-lg-12 my-2">
                        <b-form-checkbox
                            :disabled="selected == null"
                            id="checkbox-notify"
                            v-model="status_send"
                            name="checkbox-notify"
                            >
                            Anunță utilizatorul prin e-mail.
                        </b-form-checkbox>
                    </div>



                    <div class="col-lg-12 my-2">
                        <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                        <b-button variant="info" disabled v-else>
                            <b-spinner small></b-spinner>
                            <span class="sr-only">Salvam...</span>
                        </b-button>
                        <button class="btn btn-default" :class="{'disabled': once}" @click.prevent="resetAll" :disabled="once">Resetează</button>
                    </div>
                </form>
            </ValidationObserver>

        </b-modal>
  </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver} from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


  import Multiselect from 'vue-multiselect'

  import { mapGetters } from 'vuex'

export default {
    name: "AddNewCouponComponent",
    components: { Multiselect, ValidationProvider, ValidationObserver },

    data(){
        return {
            selected_user: null,
            modalShow: false,
            loadingStatus: false,
            options: [],
            selected: null,
            validation_errors: null,
            once: false,
            amount_value: 0,

            status_send: false
        }
    },

    computed: {
    ...mapGetters('coupons', [
      'getUsers',
    ])
  },

    methods: {
        addNewCoupon: function(){
            // console.log('addNewCoupon');
        },

        initializeUsers(){
            this.loadingStatus = true;

            this.$store.dispatch('coupons/initUsers').finally(() => {
              this.loadingStatus = false;
            });
        },

        selectItem(item){
            // console.log('selectat item', item);

            this.selected = item;
            // console.log('this.value este', this.selected);

            // this.$emit('categories:selected', this.selected.flat());
            // this.$emit('categories:cached', this.selected.flat());
        },
        removeItem(){
            // console.log('eliminat item');
            this.selected = null;

            // console.log('this.selected este', this.selected);
            // this.$emit('categories:selected', this.selected.flat());
            // this.$emit('categories:cached', this.selected.flat());
        },

        resetAll: function(){
            this.amount_value = 0;
            this.selected = null;
            this.selected_user = null;
            this.status_send = false;
        },

        onSubmit: function(){
            // console.log('fire');
            this.once = true;

            let user_id = this.selected ? this.selected.user_id : false;

            let formData = new FormData();
            formData.append('amount', this.amount_value);

            if(user_id) {
                formData.append('user_id', user_id);
                formData.append('notify', this.status_send);

                axios.post(`/api/coupons/store/for/user`, formData).then(response => {
                    // console.log('/api/coupons/store/for/user', response.data);

                    if(response.data.errors || response.data.validation_errors){
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    } else if(response.data.success){
                        this.$swal(
                            'Cupon creat.',
                            'Cuponul a fost generat cu succes pentru utilizatorul selectat!',
                            'success'
                        );

                        this.$store.dispatch('coupons/initCoupons');

                        this.resetAll();
                        this.$refs.observer.reset();
                        this.modalShow = false;
                    }
                }).catch((err) => {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }).finally(() => {
                    this.once = false;
                });
            } else {
                axios.post(`/api/coupons/store/generate`, formData).then(response => {
                    // console.log('/api/coupons/store/generate', response.data);
                    if(response.data.errors || response.data.validation_errors){
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    } else if(response.data.success){
                        this.$swal(
                            'Cupon creat.',
                            'Cuponul a fost generat cu succes!',
                            'success'
                        );

                        this.$store.dispatch('coupons/initCoupons');

                        this.resetAll();
                        this.$refs.observer.reset();
                        this.modalShow = false;
                    }

                }).catch((err) => {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }).finally(() => {
                    this.once = false;
                });
            }
        }
    },

    created(){
        this.initializeUsers();
    }
}
</script>

<style>

</style>