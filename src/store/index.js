import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      id: null,
      email: null,
      firstname: null,
      lastname: null,
      loggedIn: null,
      role: null
    },

    players: {}
  },
  getters: {
    user: state => {
      return state.user
    },

    players: state => {
      return state.players
    }
  },
  actions: {
    setUser ({ commit }, userData) {
      commit('setUser', userData)
    },

    logOut ({ commit }) {
      commit('logOut')
    },

    initPlayers ({ commit }) {
      let players = {}

      axios.get(process.env.VUE_APP_BASE_URL + '/api/get-players.php')
        .then(response => {
          console.log('response')
          console.log(response)
          if (response.status === 200) {
            players = response.data.players
            commit('initPlayers', players)
          } else {
            this.response = response.error
            this.errors.push(response.error)
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
          console.log(this.errors)
        })
    }
  },
  mutations: {
    setUser (state, userData) {
      state.user = userData
      window.localStorage.setItem('5_scorers_user_id', userData.id)
      window.localStorage.setItem('5_scorers_user_email', userData.email)
      window.localStorage.setItem('5_scorers_user_firstname', userData.firstname)
      window.localStorage.setItem('5_scorers_user_lastname', userData.lastname)
      window.localStorage.setItem('5_scorers_user_loggedIn', userData.loggedIn)
      window.localStorage.setItem('5_scorers_user_role', userData.role)
    },

    logOut (state) {
      console.log('logging out: ')
      state.user = {
        id: null,
        email: null,
        firstname: null,
        lastname: null,
        loggedIn: null,
        role: null
      }
      window.localStorage.removeItem('5_scorers_user_id')
      window.localStorage.removeItem('5_scorers_user_email')
      window.localStorage.removeItem('5_scorers_user_firstname')
      window.localStorage.removeItem('5_scorers_user_lastname')
      window.localStorage.removeItem('5_scorers_user_loggedIn')
      window.localStorage.removeItem('5_scorers_user_role')
    },

    initPlayers (state, players) {
      state.players = players
    }
  },
  modules: {
  }
})
