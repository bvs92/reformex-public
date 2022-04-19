<template>
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalii cerere</h3>
          <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            <a href="#" class="btn btn-warning btn-sm ml-2" @click.prevent="openModal">Modifică</a>
          </div>
        </div>
        <div class="card-body">
          <h4>Subiect: {{ demand.subject }}</h4>
          <p>Nume: {{ demand.name }}</p>
          <p>E-mail: {{ demand.email }}</p>
          <p>Telefon: {{ demand.phone }}</p>
          <hr>
          <p class="small">Dată lansare: <i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatElementTimeMethod(demand.created_at) }} <span>({{ calculateResponseTimeMethod(demand.created_at) }})</span></p>
          <p class="small">Ultima modificare: {{ formatElementTimeMethod(demand.updated_at) }} <span>({{ calculateResponseTimeMethod(demand.updated_at) }})</span></p>
          <hr>
          <p class="small">Categorii: <a href="#" class="btn btn-warning btn-sm ml-2 float-right" @click.prevent="openCategoriesModal">Modifică categorii</a></p>
          <div v-if="demand.categories && demand.categories.length">
            <span class="badge badge-info mx-1" v-for="category in demand.categories" :key="'category_' + category.id">{{ category.name }}</span>
          </div>
          <hr>
          <p>Locație: {{ demand.city }}<span v-if="demand.administrative && demand.administrative !== 'undefined'">, {{ demand.administrative }}</span> <a href="#" class="btn btn-warning btn-sm ml-2 float-right" @click.prevent="openLocationModal">Modifică locația</a></p>
          <MapDemandComponent :lat="getLat" :lng="getLng" :accessTokenMap="accessTokenMap" :key="mapKey" />
          <hr>
          <p>Mesaj:</p>
          <div>{{ demand.message }}</div>
          <hr>
          <div v-if="demand.files && demand.files.length > 0">
            <ListFilesComponent :the_files="demand.files" :the_type_path="'demands'" @file:deleted="fileDeleted" :key="filesKey" :type="'images'" />
          </div>
          <hr>
          <div v-if="demand.attachments && demand.attachments.length > 0">
            <ListFilesComponent :the_files="demand.attachments" :the_type_path="'demands'" @attachment:deleted="attachmentDeleted" :key="attachmentsKey" :type="'attachments'" />
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-lg-6">
              <p>Stare: 
                  <span class="badge badge-success" v-if="demand.state == 1">Activă</span>
                  <span class="badge badge-danger" v-else>Inactivă</span>
              </p>
            </div>
            <div class="col-lg-6">
              <a href="#" class="btn btn-info btn-sm ml-2 float-right" @click.prevent="changeState"> <i class="ti-reload"></i> Schimbă stare</a>
            </div>
          </div>
        </div>

        <b-modal v-model="modalLocation" 
        id="modal-center" 
        centered
        title="Modifică locația"
        hide-footer>
          <LocationComponent :demand_id="demand.id" @location:changed="locationChanged" :app_id="app_id" :api_key="api_key" />
        </b-modal>

        <b-modal v-model="modalCategories" 
        id="modal-center" 
        centered
        title="Modifică categorii"
        hide-footer>
          <CategoriesComponent ref="CategoriesComponent" :demand_id="demand.id" :existing="demand.categories" @categories:changed="changedCategories" />
        </b-modal>


        <b-modal v-model="modalDemand" 
        id="modal-center" 
        centered
        title="Modifică detalii cerere"
        hide-footer>
        
          <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form id="register_demand" @submit.prevent="handleSubmit(onSubmit)">
            <div class="form-group d-flex justify-content-center">
                <div class="col-lg-12">
                <label for="subject">Subiect cerere</label>
                <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                    <input type="text" 
                    v-model="subject" 
                    class="form-control" 
                    :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                    id="subject" placeholder="Subiect cerere" name="subject">
                    <span class="small text-danger">{{ errors[0] }}</span>
                </validation-provider>
                <template v-if="validation_errors">
                    <template v-if="validation_errors['subject']">
                        <span class="small text-danger" v-for="(error, index) in validation_errors['subject']" :key="'error-' + index">{{ error }}</span>
                    </template>
                </template>

                </div>
            </div>
        
            <div class="form-group d-flex justify-content-center">
                <div class="col-lg-12">
                <label for="name">Nume și Prenume</label>
                <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                    <input type="text" 
                    class="form-control" 
                    :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                    id="name" 
                    v-model="name"
                    placeholder="Nume și Prenume" 
                    name="name">
                    <span class="small text-danger">{{ errors[0] }}</span>
                </validation-provider>
                <template v-if="validation_errors">
                    <template v-if="validation_errors['name']">
                        <span class="small text-danger" v-for="(error, index) in validation_errors['name']" :key="'error-' + index">{{ error }}</span>
                    </template>
                </template>
                </div>
            </div>

            <div class="form-group d-flex justify-content-center">
              <div class="col-lg-12">
              <label for="email">Adresă de e-mail</label>
              <validation-provider rules="required|email" v-slot="{ errors, invalid, passed, touched }">
                  <input type="email" class="form-control" 
                  :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                  id="email" 
                  placeholder="exemplu@email.com" name="email"
                  v-model="email"
                  >
                  <span class="small text-danger">{{ errors[0] }}</span>
              </validation-provider>
              <template v-if="validation_errors">
                  <template v-if="validation_errors['email']">
                      <span class="small text-danger" v-for="(error, index) in validation_errors['email']" :key="'error-' + index">{{ error }}</span>
                  </template>
              </template>
              </div>
          </div>
      
          <div class="form-group d-flex justify-content-center">
              <div class="col-lg-12">
              <label for="phone">Număr de telefon</label>
              <validation-provider :rules="{ required: true }" v-slot="{ errors, touched, invalid, passed }">
              <input type="text" class="form-control" 
                  :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                  id="phone" 
                  placeholder="0722334455" name="phone"
                  v-model="phoneNumber"
                  >
                  <span class="small text-danger">{{ errors[0] }}</span>
              </validation-provider>
              <template v-if="validation_errors">
                  <template v-if="validation_errors['phone']">
                      <span class="small text-danger" v-for="(error, index) in validation_errors['phone']" :key="'error-' + index">{{ error }}</span>
                  </template>
              </template>
                        
              </div>
          </div>

            <div class="form-group d-flex justify-content-center">
              <div class="col-lg-12">
              <label for="message">Mesajul</label>
              <validation-provider rules="required|min:10" v-slot="{ errors, passed, touched, invalid }">
                  <textarea 
                  class="form-control" 
                  :class="{'is-invalid' : touched && invalid, 'is-valid': passed}"
                  name="message" id="message" 
                  v-model="description"
                  cols="30" rows="10"
                  ></textarea>
                  <span class="small text-danger">{{ errors[0] }}</span>
              </validation-provider>
              <template v-if="validation_errors">
                  <template v-if="validation_errors['message']">
                      <span class="small text-danger" v-for="(error, index) in validation_errors['message']" :key="'error-' + index">{{ error }}</span>
                  </template>
              </template>
              </div>
          </div>

          <div class="form-group d-flex justify-content-center">
              <div class="col-lg-8">
              <button v-if="!btnLoading" type="submit" class="btn btn-success btn-block" :disabled="invalid">Salvează cererea</button>
              <button v-else type="button" class="btn btn-success btn-loading btn-block" disabled="disabled">În curs de salvare</button>
              </div>
          </div>
          </form>
          </ValidationObserver>

        </b-modal>

      </div>

      
    </div>
    <div class="col-lg-6">
      <ListDemandBuyers :demand="demand" @buyer:deleted="deleteBuyer" />
      <ListDemandReports :the_demand="demand" @report:deleted="deleteReport" @change:demandStatus="changeStatus" />
      <RelaunchDemandComponent :the_demand="demand" @relaunch:demand="relaunchDemand" />
      <DeleteDemandComponent :the_demand="demand" />
    </div>
  </div>
</template>

<script>
import ListFilesComponent from "./_modules/ListFilesComponent.vue";
import LocationComponent from "./_modules/LocationComponent.vue";
import ListDemandBuyers from "./_modules/ListDemandBuyers.vue";
import RelaunchDemandComponent from "./_modules/RelaunchDemandComponent.vue";
import DeleteDemandComponent from "./_modules/DeleteDemandComponent.vue";
import ListDemandReports from "./_modules/ListDemandReports.vue";
import MapDemandComponent from "../../MapDemandComponent.vue";
import CategoriesComponent from "./_modules/CategoriesComponent.vue";

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, email, integer, min_value, min, length } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('email', {
  ...email,
  message: 'Adresa de e-mail invalida.'
});

extend('integer', {
  ...integer,
  message: 'Sunt acceptate doar valori numerice intregi.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minima acceptata este 20.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

extend('length', {
  ...length,
  message: 'Lungimea acceptata este {length} caractere.'
});


extend('phone_rule', {
    message: "Număr de telefon invalid.",
    validate: value => {
        return "Numărul de telefon nu este valid."
    }
});


export default {
    name: "AdminShowDemandComponent",

    data(){
      return {
        btnLoading: false,
        mapKey: 'map-component',
        filesKey: 'files-component',
        attachmentsKey: 'attachments-component',
        demand: null,
        modalDemand: false,
        modalLocation: false,
        modalCategories: false,
        subject: '',
        name: '',
        description: '',
        email: '',
        phoneNumber: '',

        validation_errors: null
      }
    },

    components: {
      ListFilesComponent,
      MapDemandComponent,
      LocationComponent,
      ValidationProvider,
      ValidationObserver,
      ListDemandBuyers,
      ListDemandReports,
      CategoriesComponent,
      RelaunchDemandComponent,
      DeleteDemandComponent
    },

    props: {
      the_demand: Object,
      accessTokenMap: String,
      app_id: String,
      api_key: String,
      
    },

    computed: {
      getLat: function(){
        return this.demand.lat;
      },
      getLng: function(){
        return this.demand.lng;
      },
    },

    methods: {
      checkValidationError: function(field){
        if(this.validation_errors == null || this.validation_errors.length == 0){
          return false;
        } else {
          return this.validation_errors.includes(field) ? true : false;
        }
      },

      formatElementTimeMethod: function(element){
        // return moment(element).format("DD-MM-YYYY, HH:mm");
        return moment(element).format("lll");
      },
      calculateResponseTimeMethod: function(element_time){
          let currentTime = moment().format('YYYY MM DD, HH:mm');
          let responseTime = moment(element_time).format("YYYY MM DD, HH:mm");
          var startTime = moment(responseTime, 'YYYY MM DD, HH:mm a');
          var endTime = moment(currentTime, 'YYYY MM DD, HH:mm a');
          var resultTime = startTime.diff(endTime, 'minutes');
          var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
          return asHuman;
          // return 'hehehe';
      },

      locationChanged: function(_incoming){
        // console.log('_incoming este', _incoming);
        this.demand.lat = _incoming.latlng.lat;
        this.demand.lng = _incoming.latlng.lng;
        this.demand.administrative = _incoming.administrative;
        this.demand.city = _incoming.name;

        this.mapKey += 1;
        this.modalLocation = false;
      },

      fileDeleted: function(file_id){
        this.demand.files = this.demand.files.filter(item => {
            if(item.id != file_id){
                return item;
            }
        });

        this.filesKey += 1;
      },

      attachmentDeleted: function(file_id){
        this.demand.attachments = this.demand.attachments.filter(item => {
            if(item.id != file_id){
                return item;
            }
        });

        this.attachmentsKey += 1;
      },

      openModal: function(){
        this.modalDemand = !this.modalDemand;
        this.subject = this.demand.subject;
        this.name = this.demand.name;
        this.description = this.demand.message;
        this.email = this.demand.email;
        this.phoneNumber = this.demand.phone;
      },
      openLocationModal: function(){
        this.modalLocation = !this.modalLocation;
      },

      openCategoriesModal: function(){
        this.modalCategories = !this.modalCategories;
      },

      changeState: function(){
        console.log('change state');

        this.$swal({
                title: 'Schimbă stare cerere.',
                text: "Ești sigur că vrei să schimbi starea cererii curente? Acest lucru va însemna activarea sau dezactivarea acesteia.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    axios.post(`/api/admin/demands/${this.demand.id}/change/state`).then(async response => {

                        if(response.data.success){
                            this.demand.state = response.data.state;

                            this.$swal({
                                title: 'Succes',
                                text: "Actiune executata cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

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

      onSubmit: async function(){
        this.btnLoading = true;

        let formData = new FormData();
        formData.append('name', this.name);
        formData.append('subject', this.subject);
        formData.append('email', this.email);
        formData.append('phone', this.phoneNumber);
        formData.append('message', this.description);

        await axios.post(`/api/admin/demands/${this.demand.id}/update`, formData)
        .then(response => {
          if(response.data.success){
            this.demand = response.data.demand;
            // console.log('response.data.demand este', response.data.demand);

            this.modalDemand = false;
            this.$swal(
                'Succes',
                'Detaliile cererii au fost modificate!',
                'success'
            );

          } else if(response.data.validation_errors){
            Vue.$toast.open({
                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                type: 'error',
                duration: 6000
            });

            this.validation_errors = response.data.validation_errors;
          }
        }).catch(err => {
            Vue.$toast.open({
                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                type: 'error',
                duration: 6000
            });
        }).finally(() => {
          this.btnLoading = false;
        });
    
      },


      changedCategories: function(_categories){
        // console.log('_categories este', _categories);
        this.demand.categories = _categories;
        this.modalCategories = false;
      },

      deleteBuyer: async function(){
        // axios get demand
        await this.getDemand();
      },

      deleteReport: function(report_id){
        this.demand.reports = this.demand.reports.filter(item => {
            if(item.id !== report_id){
                return item;
            }
        });
      },

      // deleteDemand: function(demand_id){
      //   console.log('demand_id', demand_id);
      //   window.location = '/admin/demands/all';
      // },

      changeStatus: async function(){
        await this.getDemand();
      },

      getDemand: async function(){
        await axios.get(`/api/admin/get/demand/${this.demand.id}`).then(response => {
          if(response.data.success){
            this.demand = response.data.demand;
          } else {
            Vue.$toast.open({
                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                type: 'error',
                duration: 6000
            });
          }
        }).catch(err => {
          Vue.$toast.open({
                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                type: 'error',
                duration: 6000
            });
        });
      },

      relaunchDemand: function(){
        if(this.demand.state != 1){
          this.demand.state = 1;
        }
      }

    },

    created(){
      this.demand = this.the_demand;
    }
}
</script>

<style>

</style>