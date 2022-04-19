<template>
  <div class="row" v-if="!loading">
    <div class="col-lg-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Cod: {{ getPersonalCoupon.code }}</li>
            <li class="list-group-item">Valoare: {{ getPersonalCoupon.amount / 100 }} RON</li>
            <li class="list-group-item">Status: {{ getStatus(getPersonalCoupon.used) }}</li>
            <li class="list-group-item">Dată creare: {{ formatData(getPersonalCoupon.created_at) }}</li>
            <li class="list-group-item" v-if="getPersonalCoupon.activated_at">Dată activare: {{ formatData(getPersonalCoupon.activated_at) }}</li>
        </ul>


        <ul class="list-group list-group-flush" v-if="getPersonalCoupon.user">
            <li class="list-group-item"><strong>Atribuit utilizatorului</strong></li>
            <li class="list-group-item">{{ getPersonalCoupon.user.last_name }} {{ getPersonalCoupon.user.first_name }}</li>
            <li class="list-group-item">{{ getPersonalCoupon.user.email }}</li>
        </ul>

    </div>
    <div class="col-lg-6">
        <div class="card" v-if="getPersonalCoupon.user && getPersonalCoupon.used == 0">
            <div class="card-header">
                Acțiuni cupon
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-block" v-if="once_click">
                    <button type="button" class="btn btn-success btn-sm">Activează cuponul</button>
                </div>

                <div class="d-grid gap-2 d-md-block" v-else>
                    <button type="button" class="btn btn-success btn-sm" @click.prevent="activateCoupon">Activează cuponul</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center" v-else>
  <b-spinner label="Spinning"></b-spinner>
</div>
</template>

<script>
import AttachCouponToUserComponent from './_modules/AttachCouponToUserComponent.vue';
import {mapGetters} from 'vuex';

export default {
    name: "ShowCouponDetailsProComponent",

    props: {
        the_coupon_id: String
    },

    data(){
        return {
            coupon_id: null,
            loading: false,
            once_delete: false,
            once_click: false
        }
    },

    computed: {
        ...mapGetters('coupons', ['getPersonalCoupon'])
    },

    components: {
        AttachCouponToUserComponent
    },

    methods: {
        getStatus: function(status){
            if(status == 0)
                return 'Neutilizat';
            else 
                return 'Utilizat';
        },

        formatData: function(element){
            return moment(element).format("DD-MM-YYYY, HH:mm");
        },

        activateCoupon: function(){
           
            this.$swal({
                title: 'Activare cupon',
                text: "Contul tău va fi creditat cu valoarea cuponului.",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    this.once_click = true;
                    axios.post(`/api/coupons/${this.the_coupon_id}/activate/pro`).then(async response => {

                        if(response.data.success){
                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            await this.$store.dispatch('coupons/initPersonalCoupon', this.the_coupon_id);


                        } else if(response.data.errors){
                            Vue.$toast.open({
                                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                                type: 'error',
                                duration: 6000
                            });
                        }

                    }).catch((error) => {
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    }).finally(() => {
                        this.once_click = false;
                    });
                }
            });

        }
    },


    created(){
        // this.coupon_id = this.the_coupon_id;
        // console.log('cuponul este', this.coupon);
        this.loading = true;
        this.$store.dispatch('coupons/initPersonalCoupon', this.the_coupon_id).finally(() => {
            this.loading = false;
        });
    }
}
</script>

<style>

</style>