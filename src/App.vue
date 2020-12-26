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
.alert {
  display: flex;
  width: 100%;
  flex-flow: row nowrap;
  align-items: center;
  justify-content: center;
  margin: 1rem 0;
  padding: 0.5rem 1rem;
  border-radius: 0.125rem;
  color: white;
}

.error {
  background-color: $error;
}
</style>
