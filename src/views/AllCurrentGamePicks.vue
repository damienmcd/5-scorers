<template>
  <div
    class="current-picks max-w-screen-xl container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12"
  >
    <div
      class="max-w-screen-xl flex flex-row items-center justify-center flex-wrap relative"
    >
      <h1
        class="current-picks__title font-sans text-lg text-center antialiased mb-6"
      >
        Current Game Picks
      </h1>
      <div
        v-if="user.role === 'admin'"
        class="current-picks__title mb-8 text-center"
      >
        <a
          href="#"
          title="Copies missing picks from last week"
          @click.prevent="getMissingGamePicks"
          class="btn--export-picks text-sm px-4 py-2 leading-none border rounded bg-green-500 text-white border-white hover:border-transparent hover:text-green-500 hover:bg-white mb-4 md:mb-0 relative"
        >
          Add Missing Picks
        </a>
        <a
          href="#"
          @click.prevent="createPicksCsv"
          class="btn--export-picks text-sm px-4 py-2 leading-none border rounded bg-green-500 text-white border-white hover:border-transparent hover:text-green-500 hover:bg-white mb-4 md:mb-0 relative"
        >
          Export Picks
        </a>
        <a
          v-show="false"
          ref="csvDownloadButton"
          href="#"
          target="_blank"
          download=""
        >
          Download CSV
        </a>
      </div>
      <div
        v-if="user.role === 'admin'"
        class="current-picks__title mb-8 text-center"
      >
          <div
            ref="missingPicksNotification"
            class="notification w-full flex items-center justify-center flex-nowrap rounded-sm px-4 py-2 mt-2 bg-green-300 text-white"
          >
            {{ missingPicksResponse }}
          </div>
      </div>

      <div
        v-if="!currentGamePicks.length"
        class="current-picks__container container flex flex-row items-start justify-center flex-wrap mb-8 p-4 shadow-lg rounded-lg bg-white"
      >
        <div
          class="current-picks__user-name w-full text-center md:text-lg md:font-medium antialiased p-2"
        >
          No picks found
        </div>
        <div v-if="currentGameScorersLoaded" class="player-picks w-full">
            <div
              class="player-picks__player w-full flex items-center justify-center sm:font-light md:font-normal p-2 bg-green-300">
              <div>Please <router-link v-if="$store.getters.user.loggedIn" to="/pick-scorers" class="text-green-500 font-semibold">Pick Scorers</router-link> for the current game</div>
            </div>
        </div>
      </div>

      <div
        v-for="currentGamePick in currentGamePicks"
        :key="currentGamePick.picks_user_id"
        :class="[{ 'flex-half': currentGamePicks.length <= 2 }]"
        class="current-picks__container container flex flex-row items-start justify-center flex-wrap mb-8 p-4 shadow-lg rounded-lg bg-white"
      >
        <div
          class="current-picks__user-name w-full text-center md:text-lg md:font-medium antialiased p-2"
        >
          {{ currentGamePick.user_firstname }}
          {{ currentGamePick.user_lastname }}
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

export default {
  name: 'PickScorers',

  props: {},
  data () {
    return {
      currentGamePicks: [],
      currentGameWeekData: [],
      currentGameScorers: [],
      matchStartedByTeam: [],
      matchFinishedByTeam: [],
      missingPicks: [],
      missingPicksResponse: 'No picks to be added',
      currentGameScorersLoaded: false,
      csvGenerated: false,
      csvLocation: '',
      errors: [],
      dataLoaded: false
    }
  },

  async beforeMount () {
    await this.getCurrentGameScorers()
    this.dataLoaded = true
    await this.getCurrentGamePicks()
    // await this.getMissingGamePicks()
  },

  methods: {
    getCurrentGameScorers () {
      this.errors = []
      const currentGameScorersFormData = new FormData()
      const gameWeekNo = parseInt(this.game.week_no)
      console.log({ gameWeekNo })
      currentGameScorersFormData.append('game_week_no', gameWeekNo)

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: currentGameScorersFormData,
        url:
          process.env.VUE_APP_BASE_URL +
          '/api/get-all-current-game-scorers.php'
      }

      this.axios(options)
        .then((response) => {
          if (
            response.data.status === 'success' &&
            response.data.game_week_data
          ) {
            this.currentGameWeekData = Array.from(response.data.game_week_data)
            this.currentGameWeekData.forEach((match) => {
              console.log({ match })
              this.matchStartedByTeam.push({
                team: match.team_h,
                started: match.started
              })
              this.matchStartedByTeam.push({
                team: match.team_a,
                started: match.started
              })
              this.matchFinishedByTeam.push({
                team: match.team_h,
                finished: match.finished_provisional
              })
              this.matchFinishedByTeam.push({
                team: match.team_a,
                finished: match.finished_provisional
              })

              const matchStats = match.stats
              if (matchStats.length) {
                const goalsScored = matchStats[0]
                if (goalsScored.h.length) {
                  goalsScored.h.forEach((scorer) => {
                    for (let index = 0; index < scorer.value; index++) {
                      this.currentGameScorers.push(scorer.element)
                    }
                  })
                }
                if (goalsScored.a.length) {
                  goalsScored.a.forEach((scorer) => {
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
        .catch((error) => {
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
      currentGamePicksFormData.append(
        'deadline_passed',
        this.gameDeadlinePassed
      )

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: currentGamePicksFormData,
        url:
          process.env.VUE_APP_BASE_URL + '/api/get-all-current-game-picks.php'
      }

      this.axios(options)
        .then((response) => {
          if (
            response.data.status === 'success' &&
            response.data.players_picks.length
          ) {
            this.currentGamePicks = response.data.players_picks
            console.log('currentGamePicks', this.currentGamePicks)
          } else {
            this.errors.push(response.data.error)
          }
        })
        .catch((error) => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
        })
    },
    getMissingGamePicks () {
      this.errors = []
      const missingGamePicksFormData = new FormData()
      const gameId = parseInt(this.game.id)
      missingGamePicksFormData.append('game_id', gameId)
      missingGamePicksFormData.append('user_role', this.user.role)

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: missingGamePicksFormData,
        url:
          process.env.VUE_APP_BASE_URL +
          '/api/add-missing-game-picks.php'
      }

      this.axios(options)
        .then((response) => {
          if (
            response.data.status === 'success' &&
            response.data.all_users &&
            response.data.users_with_picks
          ) {
            // console.log('all_users', response.data.all_users)
            // console.log('users_with_picks', response.data.users_with_picks)
            // console.log('users_without_picks', response.data.users_without_picks)
            // console.log('users_without_picks_picks', response.data.users_without_picks_picks)
            // console.log('user_without_picks_picks_count', response.data.user_without_picks_picks_count)
            // console.log('user_picks_to_add', response.data.user_picks_to_add)
            // console.log('user_picks_to_add_this_game_id', response.data.user_picks_to_add_this_game_id)
            // console.log('picks_added_message', response.data.picks_added_message)
            // this.missingPicksResponse = 'Missing picks added. Reload page to see all picks.'
            const countUsersWithoutPicks = Object.keys(response.data.users_without_picks).length
            this.missingPicksResponse = `Missing picks added for ${countUsersWithoutPicks} users. Reload page to see all picks.`
            this.$refs.missingPicksNotification.classList.add('active')
          } else {
            this.errors.push(response.data.error)
            this.missingPicksResponse = 'No picks to be added.'
            this.$refs.missingPicksNotification.classList.add('active')
          }
        })
        .catch((error) => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)

          this.missingPicksResponse = 'Error adding picks.'
          this.$refs.missingPicksNotification.classList.add('active')
        })
    },

    playerDetails (playerId) {
      const playerObject = this.players.find(
        (player) => player.value === parseInt(playerId)
      )
      return playerObject.text
    },

    playersScored (playerPicks) {
      let playersHtml = ''
      const tempScorers = [...this.currentGameScorers]
      const picksScorersOnly = [
        parseInt(playerPicks.player_1),
        parseInt(playerPicks.player_2),
        parseInt(playerPicks.player_3),
        parseInt(playerPicks.player_4),
        parseInt(playerPicks.player_5)
      ]
      let scorersTotal = 0

      for (let index = 0; index < picksScorersOnly.length; index++) {
        let playerHtml = ''
        const playerId = picksScorersOnly[index]
        const playerInScorers = tempScorers.indexOf(playerId)

        const playerDetails = this.players.find(
          ({ value }) => value === playerId
        )

        const playerPhotoId = playerDetails.photo.replace('.jpg', '')
        // const playerPhoto = 'https://resources.premierleague.com/premierleague/photos/players/110x140/p178301.png'
        const playerPhoto = `https://resources.premierleague.com/premierleague/photos/players/110x140/p${playerPhotoId}.png`

        if (playerInScorers > -1) {
          playerHtml = `
            <div
              class="player-picks__player w-full flex items-center sm:font-light md:font-normal p-2 bg-green-300">
              <img class="player-picks__player__photo" src="${playerPhoto}">
              <div class="player-picks__player__name">${this.playerDetails(playerId)}</div>
            </div>
          `
          tempScorers.splice(playerInScorers, 1)
          scorersTotal++
        } else {
          const matchTeamFinished = this.matchFinishedByTeam.find(
            ({ team }) => team === playerDetails.team_id
          )
          // console.log({ matchTeamFinished })
          const matchTeamStarted = this.matchStartedByTeam.find(
            ({ team }) => team === playerDetails.team_id
          )
          // console.log({ matchTeamStarted })

          if (matchTeamFinished && matchTeamFinished.finished) {
            playerHtml = `
              <div
                class="player-picks__player w-full flex items-center sm:font-light md:font-normal p-2 bg-red-300">
                <img class="player-picks__player__photo" src="${playerPhoto}">
                <div class="player-picks__player__name">${this.playerDetails(playerId)}</div>
              </div>
            `
          } else if (matchTeamStarted && matchTeamStarted.started) {
            playerHtml = `
              <div
                class="player-picks__player w-full flex items-center sm:font-light md:font-normal p-2 bg-yellow-300">
                <img class="player-picks__player__photo" src="${playerPhoto}">
                <div class="player-picks__player__name">${this.playerDetails(playerId)}</div>
              </div>
            `
          } else {
            playerHtml = `
              <div
                class="player-picks__player w-full flex items-center sm:font-light md:font-normal p-2">
                <img class="player-picks__player__photo" src="${playerPhoto}">
                <div class="player-picks__player__name">${this.playerDetails(playerId)}</div>
              </div>
            `
          }
        }

        playersHtml = playersHtml + playerHtml
      }

      if (scorersTotal === 5) {
        playersHtml =
          playersHtml +
          `
        <div class="player-picks__players__scorers-total flex flex-row items-center justify-center mt-4 p-2 rounded-lg">
          <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
          <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
          <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
          <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
          <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
        </div>`
      } else {
        playersHtml =
          playersHtml +
          `
        <div class="player-picks__players__scorers-total flex flex-row items-center justify-center mt-4 p-2 rounded-lg">
          <img class="player-picks__players__scorers-ball mr-2" src="/icons/ball-2.svg">
          x <span class="ml-2 player-picks__players__scorers-number">${scorersTotal}</span>
        </div>`
      }

      playersHtml = playersHtml + '</div>'

      let playerPicksHtml = ''
      if (scorersTotal === 5) {
        playerPicksHtml =
          playerPicksHtml +
          '<div class="player-picks__players player-picks__players--winner">'
      } else {
        playerPicksHtml =
          playerPicksHtml + '<div class="player-picks__players">'
      }
      playerPicksHtml = playerPicksHtml + playersHtml
      playerPicksHtml = playerPicksHtml + '</div>'

      this.dataLoaded = true

      return playerPicksHtml
    },

    async updateCorrectPicks () {
      this.currentGamePicks.forEach((userPicks) => {
        const correctPicks = this.playersScoredExport(userPicks)
        userPicks.correct_picks = correctPicks
        console.log({ userPicks })

        // Update the database
        const correctPicksFormData = new FormData()
        correctPicksFormData.append('picks_id', userPicks.picks_id)
        correctPicksFormData.append('picks_user_id', userPicks.picks_user_id)
        correctPicksFormData.append('correct_picks', correctPicks)

        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: correctPicksFormData,
          url: process.env.VUE_APP_BASE_URL + '/api/update-correct-picks.php'
        }

        this.axios(options)
          .then(response => {
            if (response.data) {
              console.log(response.data)
            }
            if (response.data.status === 'success' && response.data.message.length) {
              console.log(response.data.message)
            } else {
              this.errors.push(response.data.error)
            }
          })
          .catch(error => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)
          })
      })
    },

    async createPicksCsv () {
      console.log('Creating CSV of picks for week ' + this.game.week_no)
      const picksArray = []

      await this.updateCorrectPicks()
      // await this.getCurrentGamePicks()

      this.currentGamePicks.forEach((userPicks) => {
        const userPicksArray = []
        userPicksArray.push(
          userPicks.user_firstname + ' ' + userPicks.user_lastname
        )
        userPicksArray.push(this.playerDetails(userPicks.player_1))
        userPicksArray.push(this.playerDetails(userPicks.player_2))
        userPicksArray.push(this.playerDetails(userPicks.player_3))
        userPicksArray.push(this.playerDetails(userPicks.player_4))
        userPicksArray.push(this.playerDetails(userPicks.player_5))
        userPicksArray.push(userPicks.correct_picks)

        picksArray.push(userPicksArray)
      })
      console.log({ picksArray })

      this.errors = []

      let csv = 'User,Scorer 1,Scorer 2,Scorer 3,Scorer 4,Scorer 5,Correct Picks\n'
      picksArray.forEach((row) => {
        csv += row.join(',')
        csv += '\n'
      })
      this.$refs.csvDownloadButton.setAttribute(
        'href',
        'data:text/csv;charset=utf-8,' + encodeURIComponent(csv)
      )
      this.$refs.csvDownloadButton.setAttribute(
        'download',
        '5Scorers-picks-week-' + this.game.week_no + '.csv'
      )
      this.csvGenerated = true
      this.$refs.csvDownloadButton.click()
    },

    playersScoredExport (playerPicks) {
      const tempScorers = [...this.currentGameScorers]
      const picksScorersOnly = [
        parseInt(playerPicks.player_1),
        parseInt(playerPicks.player_2),
        parseInt(playerPicks.player_3),
        parseInt(playerPicks.player_4),
        parseInt(playerPicks.player_5)
      ]
      let scorersTotal = 0

      for (let index = 0; index < picksScorersOnly.length; index++) {
        const playerId = picksScorersOnly[index]
        const playerInScorers = tempScorers.indexOf(playerId)

        if (playerInScorers > -1) {
          tempScorers.splice(playerInScorers, 1)
          scorersTotal++
        }
      }

      return scorersTotal
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
.btn--export-picks {
  right: 0;
  top: 0;
  width: auto;
  height: auto;
}

.current-picks {
  &__title {
    flex: 1 0 100%;
    color: #37003c;
  }

  &__container {
    flex: 0 0 49%;
    background-color: #fefefe;
  }

  &__user-name {
    color: white;
    font-weight: 600;
    background-color: #37003c;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
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
    // background: white;
    border-left: .1rem solid #ebe5eb;
    border-right: .1rem solid #ebe5eb;
    border-bottom: .1rem solid #ebe5eb;

    &:first-of-type {
      border-top: .1rem solid #ebe5eb;
    }

    &__photo {
      display: inline;
      margin-right: 2rem;
      height: 100px;
      padding: 0.25rem 0.25rem 0 0.25rem;
      background-color: #eee;
      // border-top-left-radius: 0.5rem;
      // border-top-right-radius: 0.5rem;
      border-radius: 0.5rem;
      border-left: .1rem solid #ebe5eb;
      border-right: .1rem solid #ebe5eb;
      border-top: .1rem solid #ebe5eb;
    }

    @media screen and (max-width: 768px) {
      &__photo {
        display: none;
      }
    }
  }
}
</style>
