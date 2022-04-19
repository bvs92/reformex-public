<template>
  <div class="row" v-if="!loading">
    <div class="col-lg-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Cod: {{ getCoupon.code }}</li>
            <li class="list-group-item">Valoare: {{ getCoupon.amount / 100 }} RON</li>
            <li class="list-group-item">Status: {{ getStatus(getCoupon.used) }}</li>
            <li class="list-group-item">Dată creare: {{ formatData(getCoupon.created_at) }}</li>
            <li class="list-group-item" v-if="getCoupon.activated_at">Dată activare: {{ formatData(getCoupon.activated_at) }}</li>
        </ul>


        <ul class="list-group list-group-flush" v-if="getCoupon.user">
            <li class="list-group-item"><strong>Atribuit utilizatorului</strong></li>
            <li class="list-group-item">{{ getCoupon.user.last_name }} {{ getCoupon.user.first_name }}</li>
            <li class="list-group-item">{{ getCoupon.user.email }}</li>
        </ul>

    </div>
    <div class="col-lg-6">
        <div class="card" v-if="!getCoupon.user">
            <div class="card-body">
                <AttachCouponToUserComponent :coupon_id="the_coupon_id" />
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Acțiuni
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-block">
                    <button type="button" class="btn btn-success btn-sm" @click.prevent="activateCoupon" v-if="getCoupon.user && getCoupon.used == 0">Activare cupon</button>
                    <button type="button" class="btn btn-danger btn-sm" @click.prevent="eliminateCoupon" v-if="!once_delete">Elimină</button>
                    <button type="button" class="btn btn-danger btn-sm btn-disabled" disabled="disabled" v-else>Elimină</button>
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
    name: "ShowCouponDetailsComponent",

    props: {
        the_coupon_id: String
    },

    data(){
        return {
            coupon_id: null,
            loading: false,
            once_delete: false
        }
    },

    computed: {
        ...mapGetters('coupons', ['getCoupon'])
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
            console.log('activati cupon', this.getCoupon.code);

            this.$swal({
                title: 'Activare cupon',
                text: "Prin activarea cuponului curent vom credita contul tău cu valoarea cuponului.",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {

                
                    axios.post(`/api/coupons/${this.the_coupon_id}/activate/`).then(async response => {

                        if(response.data.success){
                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            await this.$store.dispatch('coupons/initCoupon', this.the_coupon_id);


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
                    });
                }
            });

        },

        eliminateCoupon: function(){
            console.log('eliminateCoupon cupon', this.getCoupon.code);
            this.once_delete = true;
            this.$swal({
                title: 'Elimină cupon',
                text: "Ești sigur că vrei să elimini acest cupon? Acțiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {

                
                    axios.post(`/api/coupons/${this.the_coupon_id}/delete/`).then(async response => {

                        if(response.data.success){
                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            window.location = '/coupons';


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
                    });
                }
            }).finally(() => {
                this.once_delete = false;
            });

        }
    },


    created(){
        // this.coupon_id = this.the_coupon_id;
        // console.log('cuponul este', this.coupon);
        this.loading = true;
        this.$store.dispatch('coupons/initCoupon', this.the_coupon_id).finally(() => {
            this.loading = false;
        });
    }
}
</script>

<style>

</style>