<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" v-if="professional"><i class="fa fa-star"></i> Lasati-i un feedback profesionistului {{ professional.complete_name }}.</h3>
        </div>
        <div class="card-body">
        <!-- <form action="{{ route('reviews.save', $timeline->demand->id) }}" method="POST"> -->
        <form @submit.prevent="registerReview">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <p class="text-center">Va rugam selectati numarul de stele.</p>
                </div>
                <div class="col-lg-12">
                    <div class="d-flex justify-content-center">
                        <star-rating text-class="custom-text" v-model="rating"></star-rating>
                    </div>
                    <div v-if="server_errors" class="d-flex justify-content-center">
                        <p class="small text-danger" v-for="(value, key) of server_errors" :key="key">
                            <template v-if="key == 'rating'">{{ value[0] }}</template>
                        </p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-group">
                        <label for="message_review">Cum vi s-a parut colaborarea cu acest profesionist? Lasati un feedback cu cateva cuvinte despre experienta dumneavoastra...</label>
                        <textarea class="form-control" id="message_review" name="message_review" rows="8" v-model="message_review"></textarea>
                        
                        <div v-if="server_errors">
                            <p class="small text-danger" v-for="(value, key) of server_errors" :key="key">
                                <template v-if="key == 'message'">{{ value[0] }}</template>
                            </p>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-azure btn-block mt-4"><i class="fa fa-star"></i> Trimite feedback </button>
                    </div>
                </div>
            </div>
        </form>

        </div>
    </div>
</template>

<script>
import StarRating from 'vue-star-rating';


export default {
    name: "CreateReview",

    components: {
        StarRating
    },
    data(){
        return {
            message_review: '',
            rating: null,
            server_errors: null
        }
    },

    props: {
        professional: Object,
        timeline: Object
    },

    methods: {
        registerReview: function(){
            

            // axios - send request & create the review.
            let fields = {
                rating: this.rating,
                message: this.message_review
            };
            axios.post(`/api/reviews/${this.timeline.id}/store`, fields).then(response => {
                    console.log("RASPUNS de la review");
                    console.log(response);

                if(response.data.review){
                    console.log("REVIEW");
                    console.log(response.data.review);
                    this.$emit('review:saved', response.data.review);

                    // reset fields
                    this.rating = null;
                    this.message_review = '';
                    this.server_errors = null;
                } else if(response.errors){
                    console.error(response.errors);
                }
            }).catch(errors => {
                console.error('aici');
                if(errors.response.status == '422'){
                    this.server_errors = errors.response.data;
                   
                }
            });
        }
    }
}
</script>