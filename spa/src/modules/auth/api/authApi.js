import axios from 'axios'
import { SPA_CONFIG } from '@/config';

export default {

    /*
      GET     users
    */
    loadUsers: function () {
        return axios.get(`${SPA_CONFIG.API_URL}/auth/users`)
    },

    /*
      GET     users/id
    */
    loadUser: function (id) {
        return axios.get(`${SPA_CONFIG.API_URL}/auth/users/${id}`)
    },

    /*
      POST     users
    */
    createUser: function (data) {
        return axios.post(`${SPA_CONFIG.API_URL}/auth/users`, data)
    },

    /*
      PUT     users/id
    */
    editUser: function (id, data) {
        return axios.put(`${SPA_CONFIG.API_URL}/auth/users/${id}`, data)
    },
}