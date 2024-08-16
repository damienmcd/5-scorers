<template>
  <div class="home container flex flex-row items-start justify-center flex-wrap min-h-fill-d px-6 pt-12">
    <div class="max-w-screen-sm flex items-center justify-center flex-wrap">

      <div class="form-wrapper container flex flex-row items-start justify-center flex-wrap mb-4 md:mb-8 p-4 md:p-6 shadow-lg rounded-lg bg-white">
        <div
          class="main-menu container flex flex-row items-start justify-around flex-wrap"
        >
          <router-link
            to="/pick-scorers"
            class="main-menu__item mb-8 p-4 rounded-lg bg-green-500 bg-gradient-to-r from-green-700 transition-all hover:bg-green-700"
          >
            <img class="main-menu__item__icon" src="/icons/player-add.svg" alt="Add Player Icon" width="75" height="75">
            <div class="text-center uppercase">Pick Scorers</div>
          </router-link>
          <router-link
            to="/current-game"
            class="main-menu__item mb-8 p-4 rounded-lg bg-green-500 bg-gradient-to-r from-green-700 transition-all hover:bg-green-700"
          >
            <img class="main-menu__item__icon" src="/icons/goal-ball.svg" alt="Add Player Icon" width="75" height="75">
            <div class="text-center uppercase">Current Game</div>
          </router-link>

          <router-link
            to="/change-password"
            class="main-menu__item mb-8 p-4 rounded-lg bg-green-500 bg-gradient-to-r from-green-700 transition-all hover:bg-green-700"
          >
            <img class="main-menu__item__icon" src="/icons/password.svg" alt="Add Player Icon" width="75" height="75">
            <div class="text-center uppercase">Change Password</div>
          </router-link>

          <div v-if="user.role == 'admin'" class="form-section-wrapper w-full flex flex-row items-start justify-around flex-wrap">
            <div class="admin-tools w-full">
              <div class="admin-tools__title w-full text-center md:text-lg md:font-medium antialiased mb-4 p-2">Admin Tools</div>
            </div>

            <router-link
              to="/admin"
              class="main-menu__item mb-4 p-4 rounded-lg bg-green-500 bg-gradient-to-r from-green-700 transition-all hover:bg-green-700"
            >
              <img class="main-menu__item__icon" src="/icons/trophy-line.svg" alt="Add Player Icon" width="75" height="75">
              <div class="text-center uppercase">Set Up Game</div>
            </router-link>

            <router-link
              to="/manage-users"
              class="main-menu__item mb-4 p-4 rounded-lg bg-green-500 bg-gradient-to-r from-green-700 transition-all hover:bg-green-700"
            >
              <img class="main-menu__item__icon" src="/icons/users.svg" alt="Add Player Icon" width="75" height="75">
              <div class="text-center uppercase">Manage Users</div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'MainMenu',
  props: {},
  data () {
    return {}
  },

  computed: {
    ...mapGetters(['user'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.main-menu {
  &__item {
    flex: 0 0 100%;
    color: white;

    &__icon {
      margin: 0 auto 1rem;
    }
  }

  @media screen and (min-width: 768px) {
    &__item {
      flex-basis: 45%;
    }
  }
}

.admin-tools, .game-menu, .user-menu {
  &__title {
    color: white;
    font-weight: 600;
    background-color: #37003c;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }
}
</style>
