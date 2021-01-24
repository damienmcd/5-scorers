<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="max-w-screen-sm flex items-center justify-center flex-wrap">
      <h1 class="font-sans text-lg antialiased font-light mb-6">Pick your 5 scorers</h1>

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap mb-8 p-6 shadow-lg rounded-lg bg-white">
        <div
          class="form-signin container flex flex-row items-start justify-center flex-wrap"
        >
          <form
            class="form-signin container flex flex-row items-start justify-center flex-wrap"
            action="#"
            @submit.prevent="selectPlayers"
          >
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="player-1" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Scorer 1</label>
                <t-rich-select id="player-1" name="player-1" :options=$store.getters.players v-model="scorers.scorer1"></t-rich-select>
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="player-2" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Scorer 2</label>
                <t-rich-select id="player-2" name="player-2" :options=$store.getters.players v-model="scorers.scorer2"></t-rich-select>
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="player-3" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Scorer 3</label>
                <t-rich-select id="player-3" name="player-3" :options=$store.getters.players v-model="scorers.scorer3"></t-rich-select>
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-2">
                <label for="player-4" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Scorer 4</label>
                <t-rich-select id="player-4" name="player-2" :options=$store.getters.players v-model="scorers.scorer4"></t-rich-select>
            </div>
            <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
                <label for="player-5" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Scorer 5</label>
                <t-rich-select id="player-5" name="player-5" :options=$store.getters.players v-model="scorers.scorer5"></t-rich-select>
            </div>
            <button class="btn py-2 px-8 rounded-sm bg-green-500 text-white transition-all hover:bg-green-700" type="submit">Submit</button>
          </form>

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
  name: 'PickScorers',
  props: {},
  data () {
    return {
      scorers: {
        scorer1: null,
        scorer2: null,
        scorer3: null,
        scorer4: null,
        scorer5: null
      },
      errors: [],
      userResponse: '',
      testScorer: 514
    }
  },

  beforeMount () {
    if (this.currentPicks.id !== '') {
      this.scorers.scorer1 = this.currentPicks.player_1
      this.scorers.scorer2 = this.currentPicks.player_2
      this.scorers.scorer3 = this.currentPicks.player_3
      this.scorers.scorer4 = this.currentPicks.player_4
      this.scorers.scorer5 = this.currentPicks.player_5
    }
  },

  methods: {
    selectPlayers () {
      this.errors = []
      if (
        this.scorers.scorer1 === null ||
        this.scorers.scorer2 === null ||
        this.scorers.scorer3 === null ||
        this.scorers.scorer4 === null ||
        this.scorers.scorer5 === null
      ) {
        this.errors.push('Please choose 5 scorers')
      } else {
        // console.log('Saving scorers')
        const pickScorersFormData = new FormData()
        pickScorersFormData.append('user_id', this.$store.getters.user.id)
        pickScorersFormData.append('game_id', 1)
        pickScorersFormData.append('scorer1', this.scorers.scorer1)
        pickScorersFormData.append('scorer2', this.scorers.scorer2)
        pickScorersFormData.append('scorer3', this.scorers.scorer3)
        pickScorersFormData.append('scorer4', this.scorers.scorer4)
        pickScorersFormData.append('scorer5', this.scorers.scorer5)

        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: pickScorersFormData,
          url: process.env.VUE_APP_BASE_URL + '/api/pick-scorers.php'
        }

        this.axios(options)
          .then(response => {
            // console.log({ response })
            if (response.data.status === 'success' && response.data.message.length) {
              // console.log('response.data')
              // console.log(response.data)
              this.userResponse = response.data.message
            } else {
              this.response = response.data.message
              this.errors.push(response.data.message)
            }
          })
          .catch(error => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)
            // console.log(this.errors)
          })
      }
    }
  },

  computed: {
    ...mapGetters(['currentPicks'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
