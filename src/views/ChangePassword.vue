<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="max-w-screen-sm flex items-center justify-center flex-wrap">
      <h1 class="font-sans text-lg antialiased font-light mb-6">Update Your Password</h1>

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap mb-8 p-6 shadow-lg rounded-lg bg-white">
        <form
          class="form-signin container flex flex-row items-start justify-center flex-wrap"
          action="#"
          @submit.prevent="updatePassword"
        >
          <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
              <label for="password" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">New Password</label>
              <input
                id="password1"
                type="password"
                name="password1"
                v-model="form.password1"
                class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
                placeholder="New Password"
                required
              >
          </div>
          <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
              <label for="password" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Confirm Password</label>
              <input
                id="password2"
                type="password"
                name="password2"
                v-model="form.password2"
                class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
                placeholder="Confirm Password"
                required
              >
          </div>
          <button class="btn py-2 px-8 rounded-sm bg-green-500 text-white transition-all hover:bg-green-700" type="submit">Update</button>
        </form>

        <div
          class="w-full flex items-center justify-center flex-nowrap my-4 py-2 px-4 rounded-sm bg-red-500 text-white"
          v-for="(error, index) in errors"
          :key="index"
        >
          {{ error }}
        </div>

          <div
            ref="notifications"
            class="notification w-full flex items-center justify-center flex-nowrap rounded-sm px-4 py-2 mt-2 bg-green-300 text-white"
          >
            {{ userResponse }}
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'ChangePassword',
  props: {},
  data () {
    return {
      form: {
        password1: '',
        password2: ''
      },
      userResponse: '',
      errors: [],
      showNotifications: false
    }
  },

  methods: {
    updatePassword () {
      this.errors = []
      console.log('Updating password')

      if (this.form.password1 !== this.form.password2) {
        this.errors.push('Passwords do not match. Please enter the same password in both input boxes.')
      } else {
        const updatePasswordFormData = new FormData()
        updatePasswordFormData.append('user_id', this.user.id)
        updatePasswordFormData.append('new_password', this.form.password1)

        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: updatePasswordFormData,
          url: process.env.VUE_APP_BASE_URL + '/api/update-password.php'
        }

        this.axios(options)
          .then(response => {
            console.log(response)
            if (response.data.status === 'success' && response.data.message.length) {
              this.userResponse = response.data.message
              this.toggleNotifications()
              window.setTimeout(() => {
                this.toggleNotifications()
              }, 3000)
            } else {
              this.errors.push(response.data.error)
            }
          })
          .catch(error => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)
          })
      }
    },

    toggleNotifications () {
      this.showNotifications = !this.showNotifications
      window.setTimeout(() => {
        if (this.showNotifications) {
          this.$refs.notifications.classList.add('active')
        } else {
          this.$refs.notifications.classList.remove('active')
        }
      }, 300)
    }
  },

  computed: {
    ...mapGetters(['user'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
