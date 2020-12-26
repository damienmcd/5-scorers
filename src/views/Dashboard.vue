<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="max-w-screen-sm flex items-center justify-center flex-wrap">
      <h1 class="font-sans text-lg antialiased font-light mb-6">Dashboard</h1>

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap p-6 shadow-lg rounded-lg bg-white">
        <div
          class="form-signin container flex flex-row items-start justify-center flex-wrap"
        >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  props: {},

  mounted () {
    this.$store.dispatch('initPlayers')
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
