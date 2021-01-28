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
.notification {
  height: 0;
  opacity: 0;
  transition: opacity 0.2s ease-in;

  &.inactive {
    height: 0;
    opacity: 0;
  }

  &.active {
    height: auto;
    opacity: 1;
  }
}
</style>
