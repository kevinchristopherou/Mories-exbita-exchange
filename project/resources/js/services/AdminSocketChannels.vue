<template>
  <div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'

  export default {

    name: 'admin-socket-channels',

    computed: mapGetters({
      adCheck: 'adCheck',
      socketId: 'getSocketId'
    }),

    watch: {
      socketId: function () {
        if (this.adCheck) {
          this.joinChannels();
        }
      }
    },

    methods: {
      //Listen Admin Events in this channels
      joinChannels: function () {
        if ('private-admin' in window.Echo.connector.channels) {
          return;
        }

        window.Echo.private('admin')
          .listen('CurrencyAccountBalanceUpdated', (e) => {
            this.$bus.$emit('UpdateDashboardCurrency', e.currency);
          })
          .listen('BuildFrontendCompleted', (e) => {
            this.toastInfo(e.msg);
          })
      }
    }
  }
</script>