<template>
  <div>

      <!-- <MultiSelectComponent :incOptions="judete" :type="'judete'" @judete:selected="judeteSelected" /> -->
    <button class="btn btn-primary btn-sm" @click.prevent="openModal">Alegere zone</button>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Alege județele de lucru">
        <div v-if="loadingStatus" class="text-center">
        <b-spinner small label="Small Spinner"></b-spinner>
        </div>
        <template v-else>
            <div class="tags" v-if="judete && judete.length > 0">
                <template v-for="(judet, index) in judete">
                    <span v-if="judet.selected == false" class="tag px-4 py-2 m-2"  :key="judet.name + '-' + index" @click="selectingJudete(judet)">{{ judet.name }}</span>
                    <span v-else class="tag tag-azure px-4 py-2 m-2"  :key="judet.name + '-' + index" @click="deselectingJudete(judet)">{{ judet.name }}</span>
                </template>
            </div>

            <div class="row mt-6">
                <div class="col-lg-12">
                    <button class="btn btn-success btn-block" @click.prevent="saveJudete">Salvează</button>
                </div>
            </div>
        </template>
        

    </b-modal>

  <!-- <pre class="language-json"><code>{{ judete  }}</code></pre> -->

  </div>
</template>

<script>
import MultiSelectComponent from './MultiSelectComponent.vue';
// import { ValidationProvider, extend, } from 'vee-validate';
// import { required } from 'vee-validate/dist/rules';

// extend('required', {
//   ...required,
//   message: 'Aceasta informatie este obligatorie.'
// });


import { mapGetters } from 'vuex'

  export default {
    name: "JudeteComponent",
    // OR register locally

    components: {
        MultiSelectComponent
    },

    data () {
      return {
        value: null,
        loadingStatus: false,
        judete: [],
        selected_judete: [],
        modalShow: false
      }
    },

    computed: {
    ...mapGetters('judete', [
      'getUserJudete',
      // ...
    ])
  },

    props: {},


    methods: {
        initializeJudete(){
            this.loadingStatus = true;
            axios.get(`/api/judete/get/all`).then(response => {
                
                this.judete = response.data.judete;
                this.judete = this.judete.map(element => {
                    element.selected = false;
                    return element;
                });
                // console.log('judetele sunt -------------', this.judete);
            }).finally(() => {
                this.loadingStatus = false;
            });

            // this.$store.dispatch('categories_explore/initCategories').finally(() => {
            //   this.loadingStatus = false;
            // });
        },

        judeteSelected: function(payload){
            // console.log('suntem in judete component', payload);
        },

        openModal: function(){
            this.resetJudete();
            this.modalShow = !this.modalShow;
            this.judete = this.judete.map(element => {
                if (this.getUserJudete.some(e => e.id == element.id)) {
                    /* vendors contains the element we're looking for */
                    element.selected = true;
                }
                return element;
            });

            this.selected_judete = this.judete.filter(element => {
                if(element.selected == true){
                    return element;
                }
            });
            // this.validation_errors = [];
        },

        selectingJudete: function(judet){

            this.judete = this.judete.map(element => {
                if(element.id == judet.id){
                    element.selected = true;
                }
                return element;
            });

            this.selected_judete.push(judet);
            // console.log(this.selected_judete);
        },

        deselectingJudete: function(judet){

            this.judete = this.judete.map(element => {
                if(element.id == judet.id){
                    element.selected = false;
                }
                return element;
            });

            this.selected_judete = this.selected_judete.filter(element => {
                if(element.id != judet.id){
                    return element;
                }
            });
            // console.log(this.selected_judete);
        },

        resetJudete: function(){
            this.judete = this.judete.map(element => {
                element.selected = false;
                return element;
            });

            this.selected_judete = [];
        },


        saveJudete: function(){
            

            this.selected_judete = this.selected_judete.map(element => {
                delete element.selected;
                return element;
            });

            // console.log('salvam judetele', this.selected_judete);

            this.loadingStatus = true;
            axios.post(`/api/judete/user/save`, {
                judete: this.selected_judete
            }).then(response => {

                if(response.data.success){
                    this.loadingStatus = true;

                    this.$store.dispatch('judete/initUserJudete').finally(() => {
                        this.loadingStatus = false;
                    });
                    this.modalShow = false;
                    this.resetJudete();
                }
            }).finally(() => {
                this.loadingStatus = false;
            });
        }

       

      
    },

    created(){
        this.initializeJudete();
        // this.options = this.incOptions;

    }
  }
</script>

