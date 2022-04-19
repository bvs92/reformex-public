<template>
<div class="row">

    <div class="col-lg-12">
        <!-- card -->
        <div class="card border-dark my-3">
            <div class="card-header">Detalii</div>
            <div class="card-body text-dark" v-if="ad">
                <div class="d-flex justify-content-between">
                    <p class="card-text" >
                        Stare: <span v-if="valabilityComputed" class="badge badge-success">Rulează</span>
                        <span v-else class="badge badge-danger">Oprit</span>
                    </p>
                    <p class="card-text" v-if="ad.processing == 1"><span class="badge badge-warning">În analiză</span></p>
                </div>
                <hr>

                <div class="card-text">
                    <p>Ultima perioadă de valabilitate aleasă: {{ ad.recent_period.days }} zile</p>
                </div>

                <div class="card-text">
                    <p>Dată creare: {{ time_show(ad.created_at) }}</p>
                </div>

                <div class="card-text">
                    <p>Dată activare: {{ activation_time }}</p>
                </div>

                <div class="card-text">
                    <p>Dată expirare: {{ expiration_time }}</p>
                </div>
                <hr>
                <div class="card-text">
                    <p>Formular de contact atașat: <span>{{ ad.has_form == 1 ? 'Da' : 'Nu' }}</span></p>
                </div>

                <div class="card-text">
                    <p>Afișare e-mail în anunț: <span>{{ ad.show_email == 1 ? 'Da' : 'Nu' }}</span></p>
                </div>
                <hr>
                <div class="card-text">
                    <p>Categorii selectate <span class="badge badge-secondary mr-1" v-for="category in ad.categories" :key="category.id">{{ category.name }}</span></p>
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
            <div class="card-body text-dark" v-if="ad">
                    <p class="card-text" >
                        Denumire firmă: {{ ad.name }}
                    </p>
                    <p class="card-text" >
                        Adresă e-mail: {{ ad.email }}
                    </p>
        
                    <p class="card-text" >
                        Telefon contact: {{ ad.phone }}
                    </p>
                    <p class="card-text" v-if="ad.website">
                        Site internet / Pagină destinație anunț: {{ ad.website }}
                    </p>
            

                    <p class="card-text" v-if="ad.location">
                        Oraș / Localitate: {{ ad.location }}
                    </p>
                    <p class="card-text" v-if="ad.cui">
                        Cod Unic de Înregistrare (CUI): {{ ad.cui }}
                    </p>
            
                    <br>
                    <div class="card-text" >
                        <p>Anunț</p>
                        {{ ad.description }}
                    </div>


            </div>
        </div><!-- end card -->
    </div>
</div>
</template> <!-- !edit_mode -->
<script>
export default {
    name: "NonEditAdRecommendComponent",

    props: ["ad"],

    computed: {
        valabilityComputed: function(){
            if(!this.ad.ends_at || this.ad.ends_at == null || this.ad.ends_at == 'null'){
                return false;
            }

            let result;
            if(moment().isBefore(this.ad.ends_at) && this.ad.status){
                result = true;
            } else {
                result = false;
            }

            return result;
        },

        expiration_time: function(){
            if(this.ad.ends_at == null || this.ad.ends_at == 'null'){
                return 'Dată nesetată.';
            }

            if(this.ad.ends_at){
                return this.formatElementTimeMethod(this.ad.ends_at);
            }
        },

        activation_time: function(){
            if(this.ad.starts_at == null || this.ad.starts_at == 'null'){
                return 'Dată nesetată.';
            }

            if(this.ad.starts_at){
                return this.formatElementTimeMethod(this.ad.starts_at);
            }
        },


        in_process: function(){
            return this.ad.processing == 1 ? true : false;
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