<template>
  <div class="admin-nav flex flex-row items-center justify-center bg-grey-100 px-2 md:px-4 lg:px-6 py-2 shadow-lg">
    <router-link
      v-if="$store.getters.user.loggedIn"
      to="/admin"
      class="block md:inline-block mt-0 text-green-500 hover:text-grey-500 text-center mr-6">
        Set Up Game
    </router-link>
    <router-link
      v-if="$store.getters.user.loggedIn"
      to="/manage-users"
      class="block md:inline-block mt-0 text-green-500 hover:text-grey-500 text-center">
        Manage Users
    </router-link>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'

export default {
  name: 'AdminNav',

  computed: {
    ...mapGetters(['game']),

    gameDeadline () {
      return moment(this.game.deadline).format('dddd, Do of MMMM YYYY, h:mma')
    }
  }
}
</script>

<style lang="scss" scoped>
.admin-nav {
  border: .1rem solid #ebe5eb;
}
</style>
