<template>
  <div>
  </div>
</template>

<script>

  import {mapGetters} from 'vuex'

  export default {

    name: 'public-socket-channels',

    computed: mapGetters({
      authCheck: 'authCheck',
      socketId: 'getSocketId'
    }),

    watch: {
      socketId: function (val) {
        this.joinChannels();
      }
    },

    methods: {
      joinChannels: function () {
        if ('markets' in window.Echo.connector.channels) {
          return;
        }
        let self = this;
        window.Echo.channel('markets')
          .listen('MarketPriceUpdated', (e) => {
            self.$store.dispatch('updateMarket', {market: e.market})
          });
      }
    }
  }
</script>