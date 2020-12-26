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
    const localStorage = window.localStorage
    const localStorageUserId = localStorage.getItem('5_scorers_user_id')
    const localStorageUserEmail = localStorage.getItem('5_scorers_user_email')
    const localStorageUserFirstname = localStorage.getItem('5_scorers_user_firstname')
    const localStorageUserLastname = localStorage.getItem('5_scorers_user_lastname')
    const localStorageUserLoggedIn = localStorage.getItem('5_scorers_user_loggedIn')
    const localStorageUserRole = localStorage.getItem('5_scorers_user_role')

    const user = {
      status: 'success',
      id: localStorageUserId,
      email: localStorageUserEmail,
      firstname: localStorageUserFirstname,
      lastname: localStorageUserLastname,
      loggedIn: localStorageUserLoggedIn,
      role: localStorageUserRole
    }

    if (
      localStorageUserId &&
      localStorageUserEmail &&
      localStorageUserFirstname &&
      localStorageUserLastname &&
      localStorageUserLoggedIn &&
      localStorageUserRole
    ) {
      this.$store.dispatch('setUser', user)
      this.$router.push('/dashboard')
    }
  }
}
</script>
