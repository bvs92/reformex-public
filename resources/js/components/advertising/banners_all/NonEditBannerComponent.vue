<template>
<div class="row">
    <div class="col-lg-6">
        <div v-if="banner" class="my-3">
            <img class="fit-image" :src="'/storage/banners/' + banner.image" style="display: block; margin: 0 auto;" /> 
        </div>
    </div>

    <div class="col-lg-6">
        <!-- card -->
        <div class="card border-dark my-3">
            <div class="card-header">Detalii</div>
            <div class="card-body text-dark" v-if="banner">
                <div class="d-flex justify-content-between">
                    <p class="card-text" >
                        Stare: <span v-if="valabilityComputed" class="badge badge-success">Rulează</span>
                        <span v-else class="badge badge-danger">Oprit</span>
                    </p>
                    <p class="card-text" v-if="banner.processing == 1"><span class="badge badge-warning">În analiză</span></p>
                </div>
                <hr>

                <div class="card-text">
                    <p>Ultima perioadă de valabilitate aleasă: {{ banner.recent_period.days }} zile</p>
                </div>

                <div class="card-text">
                    <p>Dată creare: {{ time_show(banner.created_at) }}</p>
                </div>

                <div class="card-text">
                    <p>Dată activare: {{ activation_time }}</p>
                </div>

                <div class="card-text">
                    <p>Dată expirare: {{ expiration_time }}</p>
                </div>
                <hr>
                <div class="card-text">
                    <p>Formular de contact atașat: <span>{{ banner.has_form == 1 ? 'Da' : 'Nu' }}</span></p>
                </div>

                <div class="card-text">
                    <p>Afișare e-mail în anunț: <span>{{ banner.show_email == 1 ? 'Da' : 'Nu' }}</span></p>
                </div>
                <hr>
                <div class="card-text">
                    <p>Categorii selectate <span class="badge badge-secondary mr-1" v-for="category in banner.categories" :key="category.id">{{ category.name }}</span></p>
                </div>


                

                <!-- <div v-if="recent_period" class="card-text">
                    <p>Ultima perioadă activată: {{ recent_period.days }} zile.</p>
                </div> -->
            </div>
        </div><!-- end card -->
    </div>
    <br>
    <div class="col-lg-12">
        <!-- card -->
        <div class="card border-dark mb-3">
            <div class="card-header">Informații anunț</div>
            <div class="card-body text-dark" v-if="banner">
                    <p class="card-text" >
                        Denumire firmă: {{ banner.name }}
                    </p>
                    <p class="card-text" >
                        Adresă e-mail: {{ banner.email }}
                    </p>
        
                    <p class="card-text" >
                        Telefon contact: {{ banner.phone }}
                    </p>
                    <p class="card-text" v-if="banner.website">
                        Site internet / Pagină destinație anunț: {{ banner.website }}
                    </p>
            

                    <p class="card-text" v-if="banner.location">
                        Oraș / Localitate: {{ banner.location }}
                    </p>
                    <p class="card-text" v-if="banner.cui">
                        Cod Unic de Înregistrare (CUI): {{ banner.cui }}
                    </p>
            
                    <br>
                    <div class="card-text" >
                        <p>Anunț</p>
                        {{ banner.description }}
                    </div>


            </div>
        </div><!-- end card -->
    </div>
</div>
</template> <!-- !edit_mode -->
<script>
export default {
    name: "NonEditBannerComponent",

    props: ["banner"],

    computed: {
        valabilityComputed: function(){
            if(!this.banner.ends_at || this.banner.ends_at == null || this.banner.ends_at == 'null'){
                return false;
            }

            let result;
            if(moment().isBefore(this.banner.ends_at) && this.banner.status){
                result = true;
            } else {
                result = false;
            }

            return result;
        },

        expiration_time: function(){
            if(this.banner.ends_at == null || this.banner.ends_at == 'null'){
                return 'Dată nesetată.';
            }

            if(this.banner.ends_at){
                return this.formatElementTimeMethod(this.banner.ends_at);
            }
        },

        activation_time: function(){
            if(this.banner.starts_at == null || this.banner.starts_at == 'null'){
                return 'Dată nesetată.';
            }

            if(this.banner.starts_at){
                return this.formatElementTimeMethod(this.banner.starts_at);
            }
        },


        in_process: function(){
            return this.banner.processing == 1 ? true : false;
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        time_show: function(the_time){
            if(the_time == null || the_time == 'null'){
                return 'Dată nesetată.';
            }

            if(the_time){
                return this.formatElementTimeMethod(the_time);
            }
        },
    },
}
</script>

<style scoped>
.fit-image {
    width: 100%;max-width: 440px;height: 480px!important;max-height: 480px!important;
    object-fit: cover;
}
</style>