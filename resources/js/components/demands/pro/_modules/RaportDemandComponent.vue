<template>
<div class="row" v-if="demand.report">
    <div class="col-lg-8">
        <p class="small">Reclamație: {{ demand.report.id }}</p>
        <p class="small" v-if="demand.report.status !== 0">Reclamația a fost verificată.</p>
    </div>
    <div class="col-lg-4">
        <span class="tag tag-yellow float-right" v-if="demand.report.status == 0">În curs</span>
        <span class="tag tag-red float-right" v-if="demand.report.status == 1">Cerere invalidă</span>
        <span class="tag tag-green float-right" v-if="demand.report.status == 2">Cerere validă</span>
    </div>
</div>
<div class="row" v-else>
    <div class="col-lg-6">
        <p class="small">Cerere invalidă sau falsă? O poți reclama.</p>
    </div>
    <div class="col-lg-6">
        <a href="#" class="btn btn-default btn-sm ml-2 float-right" @click.prevent="raportDemand"> <i class="ti-flag"></i> Reclamă cererea</a>
    </div>

    <b-modal v-model="modalReport"
    id="modal-center" 
    centered
    title="Reclamă această cerere."
    hide-footer>
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form id="register_report" @submit.prevent="handleSubmit(onSubmit)">
                <div class="form-group d-flex justify-content-center">
                    <div class="col-lg-12">
                    <label for="message">Mesajul reclamației</label>
                    <validation-provider rules="required|min:3" v-slot="{ errors, passed, touched, invalid }">
                        <textarea 
                        class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}"
                        name="message" id="message" 
                        v-model="report_message"
                        cols="30" rows="4"
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
                    <button v-if="!btnLoading" type="submit" class="btn btn-success btn-block" :disabled="invalid">Trimite reclamație</button>
                    <button v-else type="button" class="btn btn-success btn-loading btn-block" disabled="disabled">În curs de trimitere</button>
                    </div>
                </div>
            </form>
        </ValidationObserver>
    </b-modal>


</div>
</template>

<script>
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
    name: "RaportDemandComponent",

    props: {
        the_demand: Object
    },

    data(){
        return {
            demand: null,
            modalReport: false,

            btnLoading: false,
            report_message: '',
            validation_errors: null
        }
    },

    components: {
        ValidationProvider,
        ValidationObserver
    },

    methods: {
        raportDemand: function(){
        //   console.log('raportDemand', this.demand.uuid);
          this.modalReport = !this.modalReport;
          this.report_message = '';
      },

      onSubmit: function(){
        this.btnLoading = true;
        this.validation_errors = null;

        let formData = new FormData();
        formData.append('message', this.report_message);
        // let formData = {
        //     'message': this.report_message
        // };

        // axios.defaults.headers.common['Content-Type'] = 'application/json';
        const config = { headers: { 'Content-Type': 'multipart/form-data', 'Access-Control-Allow-Origin' : '*' } };
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

        axios.post(`/api/pro/demands/${this.demand.id}/report`, formData)
        .then(response => {
          if(response.data.success){
            this.demand = response.data.demand;
            // console.log('response.data.demand este', response.data.demand);

            this.modalReport = false;
            this.report_message = '';
            this.validation_errors = null;
            this.$refs.observer.reset();
            this.$swal(
                'Succes',
                'Ai reclamat această cerere!',
                'success'
            );

          } else if(response.data.validation_errors){
            Vue.$toast.open({
                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                type: 'error',
                duration: 6000
            });

            this.validation_errors = response.data.validation_errors;
          } else if(response.data.errors){
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
        }).finally(() => {
          this.btnLoading = false;
        });
      }
    },

    created(){
        this.demand = this.the_demand;
    }
}
</script>

<style>

</style>