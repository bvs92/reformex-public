<template>
<div>
    <div class="container">

    


    <ais-instant-search
    :search-client="searchClient"
    index-name="dev_DEMANDS"
    >

        <!-- <div class="col-lg-4">
            <ais-clear-refinements />
            <h4>Oras</h4>
            <ais-refinement-list attribute="city" />

            <h4>Categorii</h4>
            <ais-refinement-list attribute="categories" />

            <ais-configure :hitsPerPage="8" />
        </div> -->

        <div class="">
            <div class="search-panel row">
                <div class="search-panel__filters col-lg-4">
                <ais-clear-refinements class="mb-3">
                    <span slot="resetLabel">Elimina filtre</span>
                </ais-clear-refinements>

                <h4>Oras</h4>
                <!-- <ais-refinement-list attribute="city" 
                show-more
                show-more-placeholder="Mai mult" 
                searchable
                searchable-placeholder="Cauta orasul" /> -->

                <ais-refinement-list
                    attribute="city"
                    searchable
                    show-more
                    >
                    <div
                        slot-scope="{
                        items,
                        isShowingMore,
                        isFromSearch,
                        canToggleShowMore,
                        refine,
                        createURL,
                        toggleShowMore,
                        searchForItems
                        }"
                    >


                        <input @input="searchForItems($event.currentTarget.value)" class="ais-SearchBox-input mb-1" placeholder="Cauta oras">
                        <br>

                        <b-list-group>
                        <li v-if="isFromSearch && !items.length">Nu sunt rezultate.</li>
                        <b-list-group-item class="d-flex" v-for="item in items" :key="item.value">
                            <a
                            :href="createURL(item)"
                            :style="{ fontWeight: item.isRefined ?  'bold' : '' }"
                            @click.prevent="refine(item.value)"
                            class="d-flex justify-content-between align-items-center"
                            style="width: 100%;"
                            >
                            <ais-highlight attribute="item" :hit="item"/>
                            <b-badge variant="info" pill>
                                {{ item.count.toLocaleString() }}
                            </b-badge>
                            </a>
                        </b-list-group-item>
                        </b-list-group>
                        <div class="d-flex justify-content-center">
                            <button
                            @click="toggleShowMore"
                            :disabled="!canToggleShowMore"
                            class="btn btn-sm btn-default"
                            >
                            {{ !isShowingMore ? 'Arata mai mult' : 'Arata mai putin'}}
                            </button>
                        </div>
                    </div>
                    </ais-refinement-list>




                <br><br>
                <h4>Categorii</h4>
                <!-- <ais-refinement-list attribute="categories" /> -->

                <ais-refinement-list
                    attribute="categories"
                    searchable
                    show-more
                    >
                    <div
                        slot-scope="{
                        items,
                        isShowingMore,
                        isFromSearch,
                        canToggleShowMore,
                        refine,
                        createURL,
                        toggleShowMore,
                        searchForItems
                        }"
                    >


                        <input @input="searchForItems($event.currentTarget.value)" class="ais-SearchBox-input mb-1" placeholder="Cauta categorie">
                        <br>

                        <b-list-group>
                        <li v-if="isFromSearch && !items.length">Nu sunt rezultate.</li>
                        <b-list-group-item class="d-flex" v-for="item in items" :key="item.value">
                            <a
                            :href="createURL(item)"
                            :style="{ fontWeight: item.isRefined ?  'bold' : '' }"
                            @click.prevent="refine(item.value)"
                            class="d-flex justify-content-between align-items-center"
                            style="width: 100%;"
                            >
                            <ais-highlight attribute="item" :hit="item"/>
                            <b-badge variant="info" pill>
                                {{ item.count.toLocaleString() }}
                            </b-badge>
                            </a>
                        </b-list-group-item>
                        </b-list-group>
                        <div class="d-flex justify-content-center">
                            <button
                            @click="toggleShowMore"
                            :disabled="!canToggleShowMore"
                            class="btn btn-sm btn-default"
                            >
                            {{ !isShowingMore ? 'Arata mai mult' : 'Arata mai putin'}}
                            </button>
                        </div>
                    </div>
                    </ais-refinement-list>


                </div>

                <div class="search-panel__results col-lg-8">
                <ais-search-box placeholder="Cauta oras sau categorie" class="searchbox mb-6" show-loading-indicator />

                <ais-hits>
                    <div slot-scope="{ items }">
                        <!-- <li v-for="item in items" :key="item.objectID">
                        {{ item.name }}
                        </li> -->
                        <template v-for="item in items">
                        <b-card :key="item.uuid" v-if="item.status == 0" header-tag="header" footer-tag="footer">
           
                            <template #header>
                                <h6 class="mb-0">{{ item.subject }}</h6>
                            </template>

                            <div class="row">
                                <div class="col-lg-6">
                                    <b-card-text><small><i aria-hidden="true" class="fa fa-map-marker"></i> {{ item.city }}</small></b-card-text>
                                </div>
                                <div class="col-lg-6">
                                    <b-card-text class="font-small"><i aria-hidden="true" class="fa fa-clock-o"></i> {{ formatElementTimeMethod(item) }} ({{ calculateResponseTimeMethod(item) }})</b-card-text>
                                </div>

                                <div class="col-lg-6">
                                    <b-card-text>
                                        <small><i aria-hidden="true" class="fa fa-tags"></i> 
                                            <template v-if="item.categories && item.categories.length > 0">
                                                <span v-for="(category, index) in item.categories" :key="'category-' + index">
                                                    {{ category }} <template v-if="index != item.categories.length - 1">|</template>
                                                </span>
                                            </template>
                                        </small>
                                    </b-card-text>
                                </div>
                            </div>

                            <template #footer>
                                <a :href="'/demands/id/' + item.uuid" class="btn btn-info btn-sm">Vezi detalii cerere</a>
                            </template>

                            <!-- <b-link href="#" class="card-link">Another link</b-link> -->
                        </b-card>
                        </template>

                    </div>


                    
                </ais-hits>

                <div class="pagination"><ais-pagination /></div>
                </div>
            </div>
        </div>
    </ais-instant-search>

    </div>
  </div>
</template>

<script>
 // algolia instant search
import InstantSearch from 'vue-instantsearch';
// Vue.use(InstantSearch);

import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';


export default {
    name: "ExploreDemandsAlgolia",

    components: {
        InstantSearch
    },

    data() {
        return {
            searchClient: algoliasearch(
                'ADXEW4C3JQ',
                '14a88f4da064b5216f3a0d434e99eda3'
            ),

        };
    },



    methods: {
        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },

        calculateResponseTimeMethod: function(element){
            let currentTime = moment().format('YYYY MM DD, HH:mm');
            let responseTime = moment(element.created_at).format("YYYY MM DD, HH:mm");
            var startTime = moment(responseTime, 'YYYY MM DD, HH:mm a');
            var endTime = moment(currentTime, 'YYYY MM DD, HH:mm a');
            var resultTime = startTime.diff(endTime, 'minutes');
            var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
            return asHuman;
            // return 'hehehe';
        },
    }

}
</script>


<style scoped>
.font-small {
    font-size: 12px;
}
</style>