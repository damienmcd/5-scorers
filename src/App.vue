<template>
  <div id="app" class="min-h-screen bg-grey-100">
    <Nav />
    <router-view/>
  </div>
</template>

<script>
import Nav from '@/components/Nav.vue'

export default {
  name: 'App',

  components: {
    Nav
  }
}
</script>

<style lang="scss">
</style>
