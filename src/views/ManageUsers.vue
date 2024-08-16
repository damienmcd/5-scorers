<template>
  <div
    class="users max-w-screen-xl container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12"
  >
    <div
      class="max-w-screen-xl flex flex-row items-center justify-center flex-wrap relative mb-8"
    >
      <h1
        class="users__title font-sans text-lg text-center antialiased mb-6"
      >
        5 Scorers Users
      </h1>
      <div
        v-if="user.role === 'admin'"
        class="users__title mb-8 text-center"
      >
        <router-link
            v-if="user.role == 'admin'"
            to="/add-user"
            class="btn--add-user text-sm px-4 py-2 leading-none border rounded bg-green-500 text-white border-white hover:border-transparent hover:text-green-500 hover:bg-white mb-4 md:mb-0 relative"
          >
            Add User
        </router-link>
      </div>

      <div
        v-if="!users.length"
        class="users__container container flex flex-row items-start justify-center flex-wrap mb-8 p-4 shadow-lg rounded-lg bg-white"
      >
        <div
          class="users__user-name w-full text-center md:text-lg md:font-medium antialiased p-2"
        >
          No users found
        </div>
      </div>

      <div
        v-for="user in users"
        :key="user.user_id"
        class="users__container container flex flex-row items-start justify-between flex-wrap px-4 py-2 bg-white"
      >
        <div
          class="users__user-name text-center md:text-lg md:font-medium antialiased p-2"
        >
          {{ user.user_firstname }} {{ user.user_lastname }}<span v-if="user.user_role === 'admin'"> (admin)</span>
        </div>
        <div
          class="users__buttons flex flex-row flex-wrap justify-center text-center md:text-lg md:font-medium antialiased p-2"
        >
          <button @click="resetPassword(user.user_id, user.user_firstname, user.user_lastname, user.user_email)" class="btn--add-user text-sm px-4 py-2 leading-none border rounded bg-green-500 text-white border-white hover:border-transparent hover:text-green-500 hover:bg-white mr-2 relative">Reset Password</button>
          <button @click="deleteUser(user.user_id, user.user_firstname, user.user_lastname)" class="btn--add-user text-sm px-4 py-2 leading-none border rounded bg-red-500 text-white border-white hover:border-transparent hover:text-red-500 hover:bg-white relative">Delete User</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'

export default {
  name: 'PickScorers',

  props: {},
  data () {
    return {
      users: [],
      updateUserResponse: 'User updated',
      errors: [],
      dataLoaded: false
    }
  },

  async beforeMount () {
    await this.getUsers()
  },

  methods: {
    getUsers () {
      this.errors = []
      const options = {
        method: 'POST',
        headers: { 'content-type': 'application/form-data' },
        url:
          process.env.VUE_APP_BASE_URL +
          '/api/get-users.php'
      }

      this.axios(options)
        .then((response) => {
          if (
            response.data.status === 'success' &&
            response.data.users
          ) {
            this.users = Array.from(response.data.users)
          } else {
            this.errors.push(response.data.error)
          }
          this.dataLoaded = true
        })
        .catch((error) => {
          const errorOutput = { id: this.errors.length + 1, message: error }
          this.errors.push(errorOutput)

          this.dataLoaded = true
        })
    },

    async resetPassword (userId, userFirstname, userLastname, userEmail) {
      const { value } = await Swal.fire({
        title: 'Reset Password',
        text: `Are you sure you want to reset the password for ${userFirstname} ${userLastname} to the default password?`,
        icon: 'warning',
        confirmButtonText: 'Yes',
        confirmButtonColor: '#EF4444',
        showCancelButton: true
      })
      if (value) {
        const resetPasswordFormData = new FormData()
        resetPasswordFormData.append('user_id', userId)
        resetPasswordFormData.append('user_email', userEmail)

        this.errors = []
        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: resetPasswordFormData,
          url:
            process.env.VUE_APP_BASE_URL +
            '/api/reset-password.php'
        }

        this.axios(options)
          .then((response) => {
            if (
              response.data.status === 'success'
            ) {
              this.getUsers()
              Swal.fire({
                title: 'Password Updated',
                text: `${userFirstname} ${userLastname}'s password has been reset to the default password'.`,
                icon: 'success',
                confirmButtonColor: '#14B8A6'
              })
            } else {
              this.errors.push(response.data.error)
            }
            this.dataLoaded = true
          })
          .catch((error) => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)

            this.dataLoaded = true
          })
      } else {
        console.log({ value })
      }
    },

    async deleteUser (userId, userFirstname, userLastname) {
      const { value } = await Swal.fire({
        title: 'Delete User',
        text: `Are you sure you want to delete ${userFirstname} ${userLastname}?`,
        icon: 'warning',
        confirmButtonText: 'Yes',
        confirmButtonColor: '#EF4444',
        showCancelButton: true
      })
      if (value) {
        const deleteUserFormData = new FormData()
        deleteUserFormData.append('user_id', userId)

        this.errors = []
        const options = {
          method: 'POST',
          headers: { 'content-type': 'application/form-data' },
          data: deleteUserFormData,
          url:
            process.env.VUE_APP_BASE_URL +
            '/api/delete-user.php'
        }

        this.axios(options)
          .then((response) => {
            if (
              response.data.status === 'success'
            ) {
              this.getUsers()
              Swal.fire({
                title: 'User Deleted',
                text: `${userFirstname} ${userLastname} has been deleted.`,
                icon: 'success',
                confirmButtonColor: '#14B8A6'
              })
            } else {
              this.errors.push(response.data.error)
            }
            this.dataLoaded = true
          })
          .catch((error) => {
            const errorOutput = { id: this.errors.length + 1, message: error }
            this.errors.push(errorOutput)

            this.dataLoaded = true
          })
      } else {
        console.log({ value })
      }
    }
  },

  computed: {
    ...mapGetters(['game']),
    ...mapGetters(['user'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.btn--add-user {
  right: 0;
  top: 0;
  width: auto;
  height: auto;
}

.users {
  &__title {
    flex: 1 0 100%;
    color: #37003c;
  }

  &__container {
    flex: 0 1 90%;
    background-color: #fefefe;

    &:nth-child(odd) {
      background-color: #efefef;
    }

    &:hover{
      background-color: #dedede;
    }
  }
}

.flex-half {
  flex: 0 0 49%;
}
.flex-third {
  flex: 0 0 32%;
}

@media screen and (max-width: 768px) {
  .flex-third {
    flex: 0 0 49%;
  }
}

@media screen and (max-width: 375px) {
  .flex-half {
    flex: 0 0 100%;
  }
  .flex-third {
    flex: 0 0 100%;
  }
}
</style>
