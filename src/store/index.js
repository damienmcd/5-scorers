import Vue from 'vue'
import Vuex from 'vuex'

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
    }
  },
  getters: {
    user: state => {
      return state.user
    }
  },
  mutations: {
    setUser (state, userData) {
      console.log('mutation data: ')
      console.log({ userData })
      state.user = userData
    },

    logOut (state, userData) {
      console.log('logging out: ')
      state.user = {
        id: null,
        email: null,
        firstname: null,
        lastname: null,
        loggedIn: null,
        role: null
      }
    }
  },
  actions: {
    setUser ({ commit }, userData) {
      console.log('action data: ')
      console.log({ userData })
      commit('setUser', userData)
    },

    logOut ({ commit }) {
      commit('logOut')
    }
  },
  modules: {
  }
})
