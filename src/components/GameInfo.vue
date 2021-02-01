<template>
  <div class="flex items-center justify-start flex-wrap bg-grey-500 bg-gradient-to-r from-grey-700 text-white px-2 md:px-4 lg:px-6 py-2">
      <div class="w-full block flex-grow lg:flex lg:items-center lg:justify-between lg:w-auto">
          <ul class="w-full flex flex-wrap lg:flex-nowrap justify-around text-center">
            <li class="w-full lg:w-auto">Game Week: {{ game.week_no }}</li>
            <li class="w-full lg:w-auto">Deadline: {{ gameDeadline }}</li>
            <li class="w-full lg:w-auto">Jackpot: &euro;{{ game.jackpot }}</li>
          </ul>
      </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'

export default {
  name: 'GameInfo',

  computed: {
    ...mapGetters(['game']),

    gameDeadline () {
      return moment(this.game.deadline).format('dddd, Do of MMMM YYYY, h:mma')
    }
  }
}
</script>
