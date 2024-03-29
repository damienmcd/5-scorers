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

    players: {},

    game: {},

    currentPicks: {
      id: '',
      player_1: '',
      player_2: '',
      player_3: '',
      player_4: '',
      player_5: ''
    },

    gameReady: false
  },

  getters: {
    user: state => {
      return state.user
    },

    players: state => {
      return state.players
    },

    game: state => {
      return state.game
    },

    currentPicks: state => {
      return state.currentPicks
    },

    gameReady: state => {
      return state.gameReady
    }
  },

  actions: {
    setUser ({ commit }, userData) {
      commit('setUser', userData)
    },

    logOut ({ commit }) {
      commit('logOut')
    },

    setGameReady ({ commit }) {
      commit('setGameReady')
    },

    initPlayers ({ commit }) {
      let players = {}

      axios.get(process.env.VUE_APP_BASE_URL + '/api/get-players.php')
        .then(response => {
          // console.log('players')
          // console.log(response)
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
          // console.log(this.errors)
        })
    },

    getCurrentGame ({ commit }) {
      let game = {}

      axios.get(process.env.VUE_APP_BASE_URL + '/api/get-current-game.php')
        .then(response => {
          // console.log('current game')
          // console.log(response)
          if (response.status === 200) {
            game = response.data.game
            commit('initGame', game)
          } else {
            this.response = response.error
            this.errors.push(response.error)
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
          // console.log(this.errors)
        })
    },

    getCurrentGamePicks ({ commit }) {
      // console.log('Getting existing picks')

      const existingPicksFormData = new FormData()

      existingPicksFormData.append('user_id', this.state.user.id)
      existingPicksFormData.append('game_id', this.state.game.id)

      // console.log({ existingPicksFormData })
      // console.log('this.state.user.id: ' + this.state.user.id)
      // console.log('this.state.user.id: ' + this.state.game.id)

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: existingPicksFormData,
        url: process.env.VUE_APP_BASE_URL + '/api/get-current-game-picks.php'
      }

      axios(options)
        .then(response => {
          // console.log('current picks')
          // console.log(response)
          if (response.data.status === 'success') {
            const currentGamePicks = response.data.picks
            commit('setCurrentGamePicks', currentGamePicks)
          } else if (response.data.status === 'info' && response.data.message === 'No picks found') {
            // console.log('No Picks Found')
            commit('setCurrentGamePicks', this.state.currentPicks)
          } else {
            this.response = response.error
            this.errors.push(response.error)
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
          // console.log(this.errors)
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
      // console.log('logging out: ')
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
      this.dispatch('getCurrentGame')
    },

    initGame (state, game) {
      // console.log('initGame')
      // console.log({ game })
      state.game = game
      this.dispatch('getCurrentGamePicks')
    },

    setCurrentGamePicks (state, picks) {
      state.currentPicks = picks
      this.dispatch('setGameReady')
    },

    setGameReady (state) {
      state.gameReady = true
    }
  },

  modules: {
  }
})
