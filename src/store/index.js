import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {}
  },
  getters: {
    user: state => {
      return state.user
    }
  },
  mutations: {
    setUser (state, userData) {
      console.log('mutation data: ' + userData)
      state.user = userData
    }
  },
  actions: {
    setUser ({ commit }, userData) {
      console.log('action data: ' + userData)
      commit('setUser', userData)
    }
  },
  modules: {
  }
})
