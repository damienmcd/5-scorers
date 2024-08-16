<template>
  <div class="add-user home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="add-user__wrapper max-w-screen-sm flex items-center justify-center flex-wrap">
      <h1 class="add-user__title font-sans text-lg antialiased font-light mb-6">Add User</h1>

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap mb-8 p-6 shadow-lg rounded-lg bg-white">
        <form
          class="form-signin container flex flex-row items-start justify-center flex-wrap"
          action="#"
          @submit.prevent="addUser"
        >
          <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
              <label for="firstname" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Firstname <span class="text-red-500">*</span></label>
              <input
                id="firstname"
                type="text"
                name="firstname"
                v-model="form.firstname"
                class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
                placeholder="Firstname"
                required
              >
          </div>
          <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
              <label for="lastname" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Lastname <span class="text-red-500">*</span></label>
              <input
                id="lastname"
                type="text"
                name="lastname"
                v-model="form.lastname"
                class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
                placeholder="Lastname"
                required
              >
          </div>
          <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
              <label for="email" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Email Address <span class="text-red-500">*</span></label>
              <input
                id="email"
                type="email"
                name="email"
                v-model="form.email"
                class="flex-grow-1 flex-shrink-0 w-full px-4 py-2 rounded-sm border-solid border-2 border-opacity-100 border-grey-200 bg-grey-100 focus:bg-white focus:ring-offset-0 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50"
                placeholder="Email Address"
                required
              >
          </div>
          <div class="flex flex-wrap flex-grow-1 flex-shrink-0 w-full mb-4">
              <label for="admin" class="flex-grow-1 flex-shrink-0 w-full py-2 text-left">Admin User <span class="text-red-500">*</span></label>

              <div class="w-full">
                <label for="no" class="flex-grow-0 flex-shrink-1 text-left mr-2">No</label>
                <input
                  id="no"
                  type="radio"
                  name="admin"
                  value="No"
                  v-model="form.admin"
                  class=""
                  required
                >
              </div>

              <div class="w-full">
                <label for="no" class="flex-grow-0 flex-shrink-1 text-left mr-2">Yes</label>
                <input
                  id="yes"
                  type="radio"
                  name="admin"
                  value="Yes"
                  v-model="form.admin"
                  class=""
                  required
                >
              </div>
          </div>
          <button class="btn py-2 px-8 rounded-sm bg-green-500 text-white transition-all hover:bg-green-700" type="submit">Add User</button>
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
export default {
  name: 'AddUser',
  props: {},
  data () {
    return {
      form: {
        firstname: '',
        lastname: '',
        email: '',
        admin: 'no',
        adminValue: 0
      },
      userResponse: '',
      errors: [],
      showNotifications: false
    }
  },

  methods: {
    addUser () {
      this.errors = []
      console.log('Adding user')

      if (this.form.firstname !== '' && this.form.lastname !== '' && this.form.email !== '') {
        if (this.form.admin === 'No') {
          this.form.adminValue = 'user'
        } else if (this.form.admin === 'Yes') {
          this.form.adminValue = 'admin'
        }
        console.log('admin', this.form.firstname)
        console.log('admin', this.form.lastname)
        console.log('admin', this.form.email)
        console.log('admin', this.form.admin)
        console.log('adminValue', this.form.adminValue)

        const addUserFormData = new FormData()
        addUserFormData.append('firstname', this.form.firstname)
        addUserFormData.append('lastname', this.form.lastname)
        addUserFormData.append('email', this.form.email)
        addUserFormData.append('admin', this.form.adminValue)

        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: addUserFormData,
          url: process.env.VUE_APP_BASE_URL + '/api/add-user.php'
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
      } else {
        this.errors.push('Please fill in all fields.')
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
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
