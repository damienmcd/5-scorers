<template>
  <div class="max-w-screen-sm flex items-center justify-center flex-wrap">
    <h1 class="font-sans text-lg antialiased font-light mb-6">5 Scorers</h1>

    <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap p-6 shadow-lg rounded-lg bg-white">
      <form
        class="form-signin container flex flex-row items-start justify-center flex-wrap"
        action="#"
        @submit.prevent="loginUser"
      >
        <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
            <label for="email" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Email</label>
            <input
              id="email"
              type="text"
              name="email"
              v-model="form.email"
              class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
              placeholder="Email Address"
              required
              autofocus
            >
        </div>
        <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
            <label for="password" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Password</label>
            <input
              id="password"
              type="password"
              name="password"
              v-model="form.password"
              class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
              placeholder="Password"
              required
            >
        </div>
        <button class="btn py-2 px-8 rounded-sm bg-green-500 text-white transition-all hover:bg-green-700" type="submit">Login</button>
      </form>

      <div
        class="alert error"
        v-for="(error, index) in errors"
        :key="index"
      >
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script lang="js">
export default {
  name: 'Login',

  props: [],

  data () {
    return {
      form: {
        email: '',
        password: ''
      },
      response: '',
      errors: []
    }
  },

  computed: {
  },

  mounted () {
  },

  methods: {
    loginUser () {
      this.errors = []
      const loginFormData = new FormData()
      loginFormData.append('email', this.form.email)
      loginFormData.append('password', this.form.password)

      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        data: loginFormData,
        url: 'http://5scorers/login.php'
      }

      this.axios(options)
        .then(response => {
          if (response.data.status === 'success') {
            console.log('response.data')
            console.log(response.data)
            this.$store.dispatch('setUser', response.data)
            this.$router.push('/dashboard')
          } else {
            this.response = response.data.message
            this.errors.push(response.data.message)
          }
        })
        .catch(error => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)
          console.log(this.errors)
        })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
