<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="max-w-screen-sm flex items-center justify-center flex-wrap">
      <h1 class="font-sans text-lg antialiased font-light mb-6">Pick your 5 scorers</h1>

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap p-6 shadow-lg rounded-lg bg-white">
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
            class="alert error"
          >
            {{ scorers }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  props: {},
  data () {
    return {
      scorers: {
        scorer1: null,
        scorer2: null,
        scorer3: null,
        scorer4: null,
        scorer5: null
      }
    }
  },

  mounted () {
    this.$store.dispatch('initPlayers')
  },

  methods: {
    selectPlayers () {
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
