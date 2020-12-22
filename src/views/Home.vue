<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <Login class="text-center"/>
  </div>
</template>

<script>
// @ is an alias to /src
export default {
  name: 'Home',
  components: { Login: () => import(/* webpackChunkName: "Login" */ '@/components/Login.vue') },
  beforeMount () {
    if (this.$store.getters.user.loggedIn) {
      this.$router.push('/dashboard')
    }
  }
}
</script>
