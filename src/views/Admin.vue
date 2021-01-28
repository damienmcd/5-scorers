<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="max-w-screen-sm flex items-center justify-center flex-wrap">
      <h1 class="font-sans text-lg antialiased font-light mb-6">Set up game</h1>

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap mb-8 p-6 shadow-lg rounded-lg bg-white">
        <div
          class="form-signin container flex flex-row items-start justify-center flex-wrap"
        >
          <p class="mb-4">{{ game }}</p>
          <p class="mb-4">{{ currentGame }}</p>
          <form
            v-if="gameLoaded"
            class="form-signin container flex flex-row items-start justify-center flex-wrap"
            action="#"
            @submit.prevent="saveGame"
          >
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="week-no" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Week No.</label>
                <input type="number" id="week-no" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left" name="week-no" v-model="currentGame.week_no" />
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="deadline-date" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Picks Deadline</label>
                <t-datepicker id="deadline-date" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left" name="deadline-date" userFormat="F j, Y" dateFormat="Y-m-d" v-model="currentGame.deadline_date"></t-datepicker>
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <input type="time" id="deadline-time" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left" name="deadline-time" v-model="currentGame.deadline_time" />
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="jackpot" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Jackpot</label>
                <input type="number" id="jackpot" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left" name="jackpot" v-model="currentGame.jackpot" />
            </div>

            <button class="btn py-2 px-8 rounded-sm bg-green-500 text-white transition-all hover:bg-green-700" type="submit">Submit</button>
          </form>
          <!-- <p>{{ currentGame }}</p> -->
          <!-- <p>{{ currentDeadline }}</p> -->

          <div
            class="w-full flex items-center justify-center flex-nowrap my-4 py-2 px-4 rounded-sm bg-red-500 text-white"
            v-for="(error, index) in errors"
            :key="index"
          >
            {{ error }}
          </div>

          <div
            v-if="userResponse !== ''"
            class="w-full flex items-center justify-center flex-nowrap my-4 py-2 px-4 rounded-sm bg-green-300 text-white"
          >
            {{ userResponse }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Admin',
  props: {},
  data () {
    return {
      currentGame: {
        game_id: 0,
        week_no: 0,
        deadline_date: '2021-01-01',
        deadline_time: '15:00',
        jackpot: 0
      },
      gameLoaded: false,
      saveMode: 'update',
      errors: [],
      userResponse: ''
    }
  },

  beforeMount () {
    console.log('Game: ')
    console.log(this.game)
    if (this.game.deadline !== '') {
      this.currentGame.game_id = parseInt(this.game.id)
      this.currentGame.week_no = parseInt(this.game.week_no)
      this.currentGame.jackpot = parseInt(this.game.jackpot)

      this.currentGame.deadline = this.game.deadline
      const deadlineArray = this.game.deadline.split(' ')
      console.log({ deadlineArray })
      this.currentGame.deadline_date = deadlineArray[0]
      this.currentGame.deadline_time = deadlineArray[1]
    }

    this.$nextTick(() => {
      this.gameLoaded = true
    })
  },

  methods: {
    saveGame () {
      console.log('Saving game')
      if (
        this.currentGame.week_no !== parseInt(this.game.week_no) &&
        this.currentDeadline !== parseInt(this.game.deadline) &&
        this.currentGame.jackpot !== parseInt(this.game.jackpot)
      ) {
        this.saveMode = 'create'
        console.log('saveMode: ' + this.saveMode)

        // console.log('Creating new game')
        const saveGameFormData = new FormData()
        saveGameFormData.append('save_mode', this.saveMode)
        saveGameFormData.append('game_id', this.currentGame.game_id)
        saveGameFormData.append('week_no', this.currentGame.week_no)
        saveGameFormData.append('deadline_date', this.currentGame.deadline_date + ' ' + this.currentGame.deadline_time)
        saveGameFormData.append('jackpot', this.currentGame.jackpot)

        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: saveGameFormData,
          url: process.env.VUE_APP_BASE_URL + '/api/save-game.php'
        }

        this.axios(options)
          .then(response => {
            console.log({ response })
            if (response.data.status === 'success' && response.data.message.length) {
              this.userResponse = response.data.message
              this.$store.dispatch('getCurrentGame')
            } else {
              this.errors.push(response.data.error)
            }
          })
          .catch(error => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)
          })
      } else {
        this.saveMode = 'update'
        console.log('saveMode: ' + this.saveMode)

        // console.log('Creating new game')
        const saveGameFormData = new FormData()
        saveGameFormData.append('save_mode', this.saveMode)
        saveGameFormData.append('game_id', this.currentGame.game_id)
        saveGameFormData.append('week_no', this.currentGame.week_no)
        saveGameFormData.append('deadline_date', this.currentGame.deadline_date + ' ' + this.currentGame.deadline_time)
        saveGameFormData.append('jackpot', this.currentGame.jackpot)

        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: saveGameFormData,
          url: process.env.VUE_APP_BASE_URL + '/api/save-game.php'
        }

        this.axios(options)
          .then(response => {
            console.log({ response })
            if (response.data.status === 'success' && response.data.message.length) {
              this.userResponse = response.data.message
              this.$store.dispatch('getCurrentGame')
            } else {
              this.errors.push(response.data.error)
            }
          })
          .catch(error => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)
          })
      }
    }
  },

  computed: {
    ...mapGetters(['game']),
    currentDeadline () {
      return this.currentGame.deadline_date + ' ' + this.currentGame.deadline_time + ':00'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
