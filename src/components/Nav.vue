<template>
  <nav class="flex items-center justify-center flex-wrap bg-green-500 bg-gradient-to-r from-green-700 p-6">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
      <svg
        class="fill-current h-8 w-8 mr-2"
        width="54"
        height="54"
        viewBox="0 0 54 54"
        xmlns="http://www.w3.org/2000/svg">
          <path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/>
      </svg>
      <span class="font-semibold text-xl tracking-tight">5 Scorers</span>
    </div>

    <!-- <div class="block lg:hidden">
      <button class="flex items-center px-3 py-2 border rounded text-green-200 border-green-400 hover:text-white hover:border-white">
        <svg
          class="fill-current h-3 w-3"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
        </svg>
      </button>
    </div> -->

    <div class="w-full block flex-grow md:flex md:items-center md:justify-end md:w-auto">
      <div class="text-sm">
        <router-link
          v-if="$store.getters.user.loggedIn"
          to="/dashboard"
          class="block mt-4 md:inline-block md:mt-0 text-white hover:text-green-200 text-center md:text-left md:mr-6">
            Main Menu
        </router-link>
        <router-link
          v-if="$store.getters.user.loggedIn"
          to="/pick-scorers"
          class="block mt-4 md:inline-block md:mt-0 text-white hover:text-green-200 text-center md:text-left md:mr-6">
            Pick Scorers
        </router-link>
        <router-link
          v-if="$store.getters.user.loggedIn"
          to="/current-game"
          class="block mt-4 md:inline-block md:mt-0 text-white hover:text-green-200 text-center md:text-left md:mr-6">
            Current Game
        </router-link>
        <a
          v-if="$store.getters.user.loggedIn"
          href="/5scorers-user-guide-2021.pdf" target="_blank"
          class="block mt-4 md:inline-block md:mt-0 text-white hover:text-green-200 text-center md:text-left md:mr-6"
        >
          User Guide
        </a>
      </div>
      <div class="text-center">
        <a
          v-if="$store.getters.user.loggedIn"
          href="#"
          @click.prevent="logOut"
          class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-green-500 hover:bg-white mt-4 md:mt-0">
          Log Out
        </a>

        <router-link
          v-else
          to="/"
          class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-green-500 hover:bg-white mt-4 lg:mt-0">
            Log In
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Nav',

  methods: {
    logOut () {
      this.$store.dispatch('logOut')
      this.$router.push('/')
    },

    logMutation () {
      console.log('Mutation done')
    }
  },

  computed: {
    ...mapGetters(['user']),
    ...mapGetters(['game']),
    ...mapGetters(['allCurrentGamePicks'])
  }
}
</script>
