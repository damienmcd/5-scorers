<template>
  <MainMenu v-if="gameReady" />
</template>

<script>
export default {
  name: 'Dashboard',
  components: {
    MainMenu: () => import(/* webpackChunkName: "MainMenu" */ '@/components/MainMenu.vue')
    // PickScorers: () => import(/* webpackChunkName: "PickScorers" */ '@/views/PickScorers.vue')
  },
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

  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
