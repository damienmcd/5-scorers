<template>
  <PickScorers v-if="gameReady" />
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Dashboard',
  components: { PickScorers: () => import(/* webpackChunkName: "PickScorers" */ '@/views/PickScorers.vue') },
  props: {},
  data () {
    return {
      gameReady: false
    }
  },

  async beforeMount () {
    await this.$store.dispatch('initPlayers')
  },

  mounted () {
    this.$store.subscribe(mutation => {
      if (mutation.type === 'setGameReady') {
        this.gameReady = true
      }
    })
  },

  methods: {},

  computed: {
    ...mapGetters(['currentPicks'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
