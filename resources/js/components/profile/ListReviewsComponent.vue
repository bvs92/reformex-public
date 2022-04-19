<template>
<div class="text-center" v-if="loading">
    <b-spinner label="Spinning"></b-spinner>
</div>
  <div v-else>
    <ul class="widget-users row" v-if="personal_reviews">
        
        <li class="col-lg-6  col-md-6 col-sm-12 col-12" v-for="review in personal_reviews" :key="'review' + review.id">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" v-if="review.name">{{ review.name }}</h4>
                    <h4 class="card-title" v-else>Nume indisponibil</h4>
                    <div class="card-options" v-if="!review.is_reported">
                        <a @click.prevent="openModal(review.id)" class="btn btn-primary btn-sm">Reclamatie</a>
                    </div>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">
                        <a href="#" v-for="rating in 5" :key="'rating-' + rating">
                            <i class="fa fa-star text-warning" v-if="rating <= review.rating"></i>
                            <i class="fa fa-star-o text-warning mr-1" v-else></i>
                        </a>
                    </p>
                    <p class="text-center">
                        <span>{{ review.rating }} din 5 stele </span>
                    </p>
                    <p class="card-text">{{ review.message }}</p>
                    <p class="card-text text-muted">{{ formatElementTimeMethod(review.created_at) }}</p>
                </div>
                
            </div>
        </li>

        <div class="col-lg-12">
            <b-pagination style="background: white; width: 100%;" v-if="getTotal > getPerPage"
                class="justify-content-center"
                v-model="currentPage"
                :total-rows="getTotal"
                :per-page="getPerPage"
                aria-controls=""
                :pills="true"
                :hide-goto-end-buttons="false"
                @change="pageClicked"
            ></b-pagination>
        </div>
      
    </ul>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Reclama recenzie">
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min:10" v-slot="{ errors, invalid, passed, touched }">
                        <label for="description">Motiv</label>
                        <textarea name="description" 
                        id="" cols="30" rows="5" 
                        ref="description" v-model="description" 
                        class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}">
                        </textarea>
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['description']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['description']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>



                <div class="col-lg-12 my-2">
                    <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once_send">Trimite reclamatie</button>
                    <b-button variant="info" disabled v-else>
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Se trimite...</span>
                    </b-button>
                </div>
            </form>
        </ValidationObserver>
    </b-modal>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min } from 'vee-validate/dist/rules';


extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

export default {
    name: "ListReviewsComponent",

    data(){
        return {
            loading: false,
            currentPage: 1,
            modalShow: false,
            validation_errors: null,
            once_send: false,
            description: null,
            selected_id: null,
            personal_reviews: null
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    computed: {
        ...mapGetters('reviews', ['getPersonalReviews', 'getCurrentPage', 'getFrom', 'getLastPage', 'getPerPage', 'getTotal',]),
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        pageClicked: function(page){
            this.$store.dispatch('reviews/getPersonalReviewsFromPage', page);
            this.currentPage = this.getCurrentPage;
        },

        openModal: function(id){
            console.log(id);
            this.modalShow = !this.modalShow;
            this.selected_id = id;
            this.reset();
        },

        reset: function(){
            this.validation_errors = null;
            this.description = null;
        },

        onSubmit: function(){
            console.log('fire.');
            this.validation_errors = null;
            this.once_send = true;
            let self = this;
            let formData = new FormData();
            formData.append('description', this.description);
            axios.post(`/api/reviews/reports/${this.selected_id}`, formData).then(response => {
                if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.success){
                    // mark report review as send - ascunde buton de raportare.
                    this.personal_reviews = this.personal_reviews.map(function(item){
                        if(item.id == self.selected_id){
                            item.is_reported = true;
                        }
                        return item;
                    });
                    this.modalShow = false;
                    this.reset();

                    this.$swal(
                        'Recenzia a fost raportata.',
                        'Recenzia va fi eliminata daca se constata ca va este lezata reputatia pe nedrept.',
                        'success'
                    );

                }
            }).finally(() => {
                this.once_send = false;
            });
        }
    },

    created(){
        this.loading = true;
        this.$store.dispatch('reviews/getPersonalReviewsFromPage').then(() => {
            this.personal_reviews = this.getPersonalReviews;
        }).finally(() => {
            this.loading = false;
        });
    }
}
</script>

<style>

</style>