<template>
  <PickScorers />
</template>

<script>
export default {
  name: 'Dashboard',
  components: { PickScorers: () => import(/* webpackChunkName: "PickScorers" */ '@/views/PickScorers.vue') },
  props: {},
  data () {
    return {}
  },

  async beforeMount () {
    await this.$store.dispatch('initPlayers')
    await this.$store.dispatch('getCurrentGame')
  },

  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
