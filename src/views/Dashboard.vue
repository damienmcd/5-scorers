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

  mounted () {
    this.$store.dispatch('initPlayers')
  },

  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
