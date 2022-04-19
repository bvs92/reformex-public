<template>
  <div class="ml-5">
    <b-form-checkbox v-model="daily_reminder" 
    switch size="md"
     class="col-lg-12 my-4 ml-4"
     @change="toggle('daily_reminder')"
     >
     Notificări zilnice cu proiecte recente disponibile</b-form-checkbox>
    <b-form-checkbox v-model="each_demand" 
    switch size="md"
     class="col-lg-12 my-4 ml-4"
     @change="toggle('each_demand')"
     >
     Notificări la fiecare cerere nouă lansată de client (care corespunde profilului meu)</b-form-checkbox>
    <b-form-checkbox v-model="promotion" 
    switch size="md"
     class="col-lg-12 my-4 ml-4"
     @change="toggle('promotion')"
     >
     Notificări despre promoții și alte noutăți</b-form-checkbox>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "NotificationSettingsComponent",

    data() {
      return {
       daily_reminder: false,
       each_demand: false,
       promotion: false
      }
    },

    methods: {
        async toggle(type){
            // this.daily_reminder = !this.daily_reminder;
            await axios
            .post('/api/notification/settings/toggle', {type: type})
            .then(response => {
                if(response.data.success){
                    Vue.$toast.open({
                        message: 'Succes. Noile setări au fost salvate.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
                } else {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            })
            .catch(error => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            });
        }
    },


    created(){
        axios.get('/api/notification/settings')
            .then(response => {
                if(response.data.settings){
                    this.daily_reminder = Boolean(response.data.settings.daily_reminder);
                    this.each_demand = Boolean(response.data.settings.each_demand);
                    this.promotion = Boolean(response.data.settings.promotion);
                } else {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch(err => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            });
    }

}
</script>

<style>

</style>