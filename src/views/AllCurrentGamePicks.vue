<template>
  <div class="current-picks max-w-screen-xl container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
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
        <div class="player-picks w-full">
          <div class="player-picks__players">
            <div
              :class="[{'bg-green-300': playerScored(currentGamePick.player_1) }]"
              class="player-picks__player w-full text-center p-2">
              {{ playerDetails(currentGamePick.player_1) }}
            </div>
            <div
              :class="[{'bg-green-300': playerScored(currentGamePick.player_2) }]"
              class="player-picks__player w-full text-center p-2">
              {{ playerDetails(currentGamePick.player_2) }}
            </div>
            <div
              :class="[{'bg-green-300': playerScored(currentGamePick.player_3) }]"
              class="player-picks__player w-full text-center p-2">
              {{ playerDetails(currentGamePick.player_3) }}
            </div>
            <div
              :class="[{'bg-green-300': playerScored(currentGamePick.player_4) }]"
              class="player-picks__player w-full text-center p-2">
              {{ playerDetails(currentGamePick.player_4) }}
            </div>
            <div
              :class="[{'bg-green-300': playerScored(currentGamePick.player_5) }]"
              class="player-picks__player w-full text-center p-2">
              {{ playerDetails(currentGamePick.player_5) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'

export default {
  name: 'PickScorers',
  props: {},
  data () {
    return {
      currentGamePicks: [],
      currentGameWeekData: [],
      currentGameScorers: [],
      errors: []
    }
  },

  async beforeMount () {
    await this.getCurrentGameScorers()
    await this.getCurrentGamePicks()
  },

  methods: {
    getCurrentGameScorers () {
      this.errors = []
      // console.log('Getting current game picks')
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
            // console.log(this.currentGameWeekData)
            this.currentGameWeekData.forEach(match => {
              const matchStats = match.stats
              // console.log({ matchStats })
              if (matchStats.length) {
                const goalsScored = matchStats[0]
                console.log({ goalsScored })
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
          } else {
            this.errors.push(response.data.error)
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
        })
    },

    getCurrentGamePicks () {
      this.errors = []
      // console.log('Getting current game picks')
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

    playerScored (playerId) {
      console.log(playerId)
      console.log(this.currentGameScorers)
      const playerInScorers = this.currentGameScorers.includes(playerId)
      console.log({ playerInScorers })
      return playerInScorers
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

.player-picks {
  box-sizing: border-box;

  &__player {
    border-left: solid 1px #cccccc;
    border-right: solid 1px #cccccc;
    border-bottom: solid 1px #cccccc;

    &:first-of-type {
      border-top: solid 1px #cccccc;
    }
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
