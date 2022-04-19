<template>
  <div v-if="!loading">
      <template v-if="getTotalPersonalPendingCouponsRequests > 0">
        <a id="add__new__coupon" @click.prevent="modalShow = !modalShow" class="btn btn-md btn-primary"><i class="fa fa-plus"></i> Solicită cupon nou</a>
        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Cere un nou cupon">

            <h4 class="text-center">Există deja o solicitare în curs.</h4>
            <p class="text-center">Poți face o nouă solicitare după verificarea celei curente.</p>
            </b-modal>
      </template>
      <template v-else>
          <a href="" id="add__new__coupon" class="btn btn-md btn-primary" @click.prevent="modalShow = !modalShow"><i class="fa fa-plus"></i> Solicită cupon nou</a>
            <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Solicita un nou cupon">

            <h4 class="text-center">Solicitare cupon nou</h4>
            <div class="col-lg-12 my-2 text-center">
                    <button class="btn btn-success" v-if="!once" @click.prevent="requestCoupon">Solicită cupon</button>
                    <b-button variant="info" disabled v-else>
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Se trimite solicitarea...</span>
                    </b-button>
                </div>
            </b-modal>
      </template>
      
  </div>
</template>

<script>

import {mapGetters} from 'vuex';

export default {
    name: "RequestCouponComponent",


    data(){
        return {
            loading: false,
            modalShow: false,
            once: false,
            status_send: false
        }
    },

    computed: {
        ...mapGetters('coupons_requests', ['getPersonalPendingCouponsRequests', 'getTotalPersonalPendingCouponsRequests'])
    },

    methods: {


        requestCoupon: function(){
            // console.log('fire');
            this.once = true;

            axios.post(`/api/coupons/requests/store`).then(response => {
                if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                } else if(response.data.success){
                    this.$swal(
                        'Solicitare trimisă.',
                        'Ai făcut o nouă solicitare de cupon.',
                        'success'
                    );

                    this.$store.dispatch('coupons_requests/initPersonalPendingCouponsRequests');

                    this.$store.dispatch('coupons_requests/initPersonalCouponsRequests');
          
                    this.modalShow = false;
                }

            }).catch((err) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });

        }
    },

    created(){
        this.loading = true;
        this.$store.dispatch('coupons_requests/initPersonalPendingCouponsRequests').finally(() => {
            this.loading = false;
        });
    }
}
</script>

<style>

</style>