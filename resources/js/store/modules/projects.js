import axios from 'axios';

export default {
    namespaced: true,

    state: {
        personal_projects: [],
        total_personal: 0,
        projects: [],
        total_projects: 0,
        project: null
    },

    getters: {


        getPersonalProjects: function(state){
            return state.personal_projects;
        },

        getTotalPersonalProjects: function(state){
            return state.total_personal;
        },

        getProjects: function(state){
            return state.projects;
        },

        getTotalProjects: function(state){
            return state.total_projects;
        },

        getProject: function(state){
            return state.project;
        }

    },

    actions: {

        initPersonalProjects: async function({commit}){
            await axios.get('/api/work-projects/initialize/personal').then(async response => {
                // console.log('preluam personal projects', response.data);
                if(response.data.projects){
                    let projects = Object.values(response.data.projects);

                    await commit('set_personal_projects', projects);
                    await commit('set_total_personal_projects', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },
        
        initProjectsByCategory: async function({commit}, _uuid){
            await axios.get('/api/work-projects/initialize/category/' + _uuid).then(async response => {
                // console.log('preluam projects pentru categorie', response.data);
                if(response.data.projects){
                    let projects = Object.values(response.data.projects);

                    await commit('set_projects', projects);
                    await commit('set_total_projects', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },


        initProject: async function({commit}, uuid){
            await axios.get('/api/work-projects/get/' + uuid).then(async response => {
                // console.log('preluam proiectul', response.data);
                if(response.data.project){
                    await commit('set_project', response.data.project);
                }
            });
        },

    },

    mutations: {
   
        set_personal_projects: function(state, _projects){
            state.personal_projects = _projects;
        },

     

        set_total_personal_projects: function(state, _total_projects){
            state.total_personal = _total_projects;
        },
        
        set_projects: function(state, _projects){
            state.projects = _projects;
        },

     

        set_total_projects: function(state, _total_projects){
            state.total_projects = _total_projects;
        },

        set_project: function(state, _project){
            state.project = _project;
        }

    },


}