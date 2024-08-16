<template>
  <div id="app" class="min-h-screen bg-grey-100">
    <Nav />
    <GameInfo v-if="showGameInfo" />
    <AdminNav v-if="showAdminNav" />
    <router-view/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Nav from '@/components/Nav.vue'
import AdminNav from '@/components/AdminNav.vue'
import GameInfo from '@/components/GameInfo.vue'

export default {
  name: 'App',

  components: {
    Nav,
    AdminNav,
    GameInfo
  },

  computed: {
    ...mapGetters(['user']),
    showAdminNav () {
      return this.user.role === 'admin'
    },
    showGameInfo () {
      return this.$route.path !== '/'
    }
  }
}
</script>

<style lang="scss">
.notification {
  height: 0;
  opacity: 0;
  transition: opacity 0.2s ease-in;

  &.inactive {
    height: 0;
    opacity: 0;
  }

  &.active {
    height: auto;
    opacity: 1;
  }
}
</style>
