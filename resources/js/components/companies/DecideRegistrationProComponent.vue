<template>
<div>
    <div class="card mb-3" v-if="the_user.status == 0">
        <div class="card-header">
            <h3 class="card-title ">Finalizare proces înscriere</h3>
        </div>

        <div class="card-body">
            <button class="btn btn-success btn-loading" v-if="once">Acceptă înscriere</button>
            <button class="btn btn-success" @click.prevent="acceptRegistration" v-else>Acceptă înscriere</button>
            <button class="btn btn-danger" @click.prevent="openRefuseModal">Refuză înscriere</button>
            <hr>
            <div v-if="the_user.registration">
                <p>Status: <span class="tag tag-default" v-if="the_user.registration.status == 0">netrimis</span> <span class="tag tag-warning" v-if="the_user.registration.status == 1">în curs</span> <span class="tag tag-danger" v-else-if="the_user.registration.status == 2">refuzat</span></p>
                <p v-if="the_user.registration.message">Motiv: {{ the_user.registration.message }}</p>
                <p class="text-muted" v-if="the_user.registration.created_at">{{ formatElementTimeMethod(the_user.registration.created_at) }}</p>
            </div>
        </div>

        <b-modal v-model="showModal" hide-footer id="modal-center" centered title="Refuzati utilizatorul">

            <div class="form-group">
                <label for="exampleFormControlTextarea1">Motiv respingere (opțional)</label>
                <textarea class="form-control" id="message" rows="3" v-model="message"></textarea>
            </div>

            <button class="btn btn-danger" @click.prevent="refuseRegistration">Refuză înscriere</button>
        </b-modal>

    </div>
    <div class="card mb-3" v-else>
        <div class="card-body">
            <h6>Acest cont este activ.</h6>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "DecideRegistrationProComponent",

    data(){
        return {
            showModal: false,
            message: '',
            once: false
        }
    },

    props: {
        the_user: Object
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        acceptRegistration: function(){
            // console.log('accept', this.the_user);

            this.once = true;

            let formData = new FormData();
            formData.append('id', this.the_user.id);

            axios.post(`/api/companies/accept`, formData).then(response => {
                // console.log('raspuns', response.data);
                if(response.data.success){
                    this.$swal({
                        title: 'Utilizator acceptat',
                        text: "Utilizatorul a fost acceptat. Pagina se va reîncărca automat.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });

                    location.reload();
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).finally(() => {
                this.once = false;
            });
        },

        openRefuseModal: function(){
            this.showModal = true;
            this.message = '';
        },

        refuseRegistration: function(){
            // console.log('refuse', this.the_user);

            let formData = new FormData();
            formData.append('id', this.the_user.id);
            formData.append('message', this.message);

            axios.post(`/api/companies/refuse`, formData).then(response => {
                // console.log('raspuns', response.data);

                if(response.data.success){
                    this.showModal = false;

                    this.$swal({
                        title: 'Utilizator refuzat',
                        text: "Utilizatorul a fost refuzat. Pagina se va reîncărca automat.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });


                    location.reload();
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            });
        }
    }
}
</script>

<style>

</style>