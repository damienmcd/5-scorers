<template>
  <div class="current-picks max-w-screen-xl container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <Loader v-show="!dataLoaded" />
    <div class="max-w-screen-xl flex flex-row items-center justify-center flex-wrap">
      <h1 class="current-picks__title font-sans text-lg text-center antialiased font-light mb-6">Current Game Picks</h1>

      <div
        v-for="currentGamePick in currentGamePicks"
        :key="currentGamePick.picks_user_id"
        :class="[{'flex-half': user.role === 'user' }, {'flex-third': user.role === 'admin' }, {'flex-third': gameDeadlinePassed }]"
        class="current-picks__container container flex flex-row items-start justify-center flex-wrap mb-8 p-4 shadow-lg rounded-lg bg-white"
      >
        <div class="current-picks__user-name w-full text-center antialiased font-light mb-2">
          {{ currentGamePick.user_firstname }} {{ currentGamePick.user_lastname }}
        </div>
        <div v-if="currentGameScorersLoaded" class="player-picks w-full">
          <div v-html="playersScored(currentGamePick)"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'
import Loader from '../components/Loader'

export default {
  name: 'PickScorers',

  components: {
    Loader
  },

  props: {},
  data () {
    return {
      currentGamePicks: [],
      currentGameWeekData: [],
      currentGameScorers: [],
      currentGameScorersLoaded: false,
      errors: [],
      dataLoaded: false
    }
  },

  async beforeMount () {
    await this.getCurrentGameScorers()
    await this.getCurrentGamePicks()
  },

  methods: {
    getCurrentGameScorers () {
      this.errors = []
      const currentGameScorersFormData = new FormData()
      const gameWeekNo = parseInt(this.game.week_no) + 1
      // const gameWeekNo = parseInt(this.game.week_no)
      currentGameScorersFormData.append('game_week_no', gameWeekNo)

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: currentGameScorersFormData,
        url: process.env.VUE_APP_BASE_URL + '/api/get-all-current-game-scorers.php'
      }

      this.axios(options)
        .then(response => {
          if (response.data.status === 'success' && response.data.game_week_data) {
            this.currentGameWeekData = Array.from(response.data.game_week_data)
            this.currentGameWeekData.forEach(match => {
              const matchStats = match.stats
              if (matchStats.length) {
                const goalsScored = matchStats[0]
                if (goalsScored.h.length) {
                  goalsScored.h.forEach(scorer => {
                    for (let index = 0; index < scorer.value; index++) {
                      this.currentGameScorers.push(scorer.element)
                    }
                  })
                }
                if (goalsScored.a.length) {
                  goalsScored.a.forEach(scorer => {
                    for (let index = 0; index < scorer.value; index++) {
                      this.currentGameScorers.push(scorer.element)
                    }
                  })
                }
              }
            })

            this.currentGameScorersLoaded = true
          } else {
            this.errors.push(response.data.error)

            this.currentGameScorersLoaded = true
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)

          this.currentGameScorersLoaded = true
        })
    },

    getCurrentGamePicks () {
      this.errors = []
      const currentGamePicksFormData = new FormData()
      currentGamePicksFormData.append('game_id', this.game.id)
      currentGamePicksFormData.append('user_id', this.user.id)
      currentGamePicksFormData.append('user_role', this.user.role)
      currentGamePicksFormData.append('deadline_passed', this.gameDeadlinePassed)

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: currentGamePicksFormData,
        url: process.env.VUE_APP_BASE_URL + '/api/get-all-current-game-picks.php'
      }

      this.axios(options)
        .then(response => {
          if (response.data.status === 'success' && response.data.players_picks.length) {
            this.currentGamePicks = response.data.players_picks
          } else {
            this.errors.push(response.data.error)
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
        })
    },

    playerDetails (playerId) {
      const playerObject = this.players.find(player => player.value === parseInt(playerId))
      return playerObject.text
    },

    playersScored (playerPicks) {
      let playersHtml = ''
      console.warn(playerPicks.user_firstname + ' ' + playerPicks.user_lastname)
      const tempScorers = [...this.currentGameScorers]
      console.log(this.currentGameScorers)
      const picksScorersOnly = [
        parseInt(playerPicks.player_1), parseInt(playerPicks.player_2), parseInt(playerPicks.player_3), parseInt(playerPicks.player_4), parseInt(playerPicks.player_5)
      ]
      console.log({ picksScorersOnly })
      let scorersTotal = 0

      for (let index = 0; index < picksScorersOnly.length; index++) {
        let playerHtml = ''
        const player = picksScorersOnly[index]
        const playerInScorers = tempScorers.indexOf(picksScorersOnly[index])
        if (playerInScorers > -1) {
          console.info({ tempScorers })
          playerHtml = `
            <div
              class="player-picks__player w-full text-center p-2 bg-green-300">
              ${this.playerDetails(player)}
            </div>
          `
          tempScorers.splice(playerInScorers, 1)
          console.info({ tempScorers })
          scorersTotal++
        } else {
          playerHtml = `
            <div
              class="player-picks__player w-full text-center p-2">
              ${this.playerDetails(player)}
            </div>
          `
        }

        playersHtml = playersHtml + playerHtml
      }
      console.log('playersHtml:')
      console.log(playersHtml)

      playersHtml = playersHtml + `
      <div class="player-picks__players__scorers-total flex flex-row items-center justify-center mt-4 p-2">
        <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
         x <span class="ml-2 player-picks__players__scorers-number">${scorersTotal}</span>
      </div>`

      playersHtml = playersHtml + '</div>'

      let playerPicksHtml = ''
      if (scorersTotal === 5) {
        playerPicksHtml = playerPicksHtml + '<div class="player-picks__players player-picks__players--winner">'
      } else {
        playerPicksHtml = playerPicksHtml + '<div class="player-picks__players">'
      }
      playerPicksHtml = playerPicksHtml + playersHtml
      playerPicksHtml = playerPicksHtml + '</div>'

      this.dataLoaded = true

      return playerPicksHtml
    }
  },

  computed: {
    ...mapGetters(['game']),
    ...mapGetters(['players']),
    ...mapGetters(['user']),

    gameDeadline () {
      return moment(this.game.deadline).format('dddd, Do of MMMM YYYY, h:mma')
    },

    gameDeadlinePassed () {
      return moment() > moment(this.game.deadline)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.current-picks {
  &__title {
    flex: 1 0 100%;
  }

  &__container {
    flex: 0 0 49%;
  }
}

.flex-half {
  flex: 0 0 49%;
}
.flex-third {
  flex: 0 0 32%;
}

@media screen and (max-width: 768px) {
  .flex-third {
    flex: 0 0 49%;
  }
}

@media screen and (max-width: 375px) {
  .flex-half {
    flex: 0 0 100%;
  }
  .flex-third {
    flex: 0 0 100%;
  }
}
</style>

<style lang="scss">
.player-picks {
  box-sizing: border-box;

  &__players {
    &__scorers-ball {
      display: inline-block;
      width: 30px;
      height: 30px;
    }

    &__scorers-number {
      font-size: 21px;
    }

    &--winner {
      .player-picks__players__scorers-total {
        background-color: rgba(253, 230, 138, var(--tw-bg-opacity));
      }
    }
  }

  &__player {
    border-left: solid 1px #cccccc;
    border-right: solid 1px #cccccc;
    border-bottom: solid 1px #cccccc;

    &:first-of-type {
      border-top: solid 1px #cccccc;
    }
  }
}
</style>
