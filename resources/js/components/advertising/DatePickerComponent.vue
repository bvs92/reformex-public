<template>
  <div>
    <b-form-datepicker v-model="value" :min="min" :max="max" locale="ro" placeholder="Selectează ziua de începere"></b-form-datepicker>
  </div>
</template>

<script>
  export default {
        name: "DatePickerComponent",
        
        data() {
            const now = new Date()
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            const maximumDate = new Date();
            maximumDate.setDate(maximumDate.getDate() + 7)
            // maximumDate = maximumDate.getDate() + 4
            // today
            const minDate = new Date(today)
            //   minDate.setMonth(minDate.getMonth() - 2)
            //   minDate.setDate(15)
            // 15th in two months
            const maxDate = new Date(today)
            // const maxDate = now().addDays(5)
            // maxDate.setMonth(maxDate + 5)
            // maxDate.setDate(now().addDays(5))

            return {
                value: '',
                min: minDate,
                max: maximumDate
            }
        }
  }
</script>