<template>
  <div>
    <public-socket-channels></public-socket-channels>
    <private-socket-channels></private-socket-channels>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'
  import Echo from 'laravel-echo';
  import PublicSocketChannels from './../services/PublicSocketChannels'
  import PrivateSocketChannels from './../services/PrivateSocketChannels'

  export default {

    name: 'socket',
    components: {
      PublicSocketChannels,
      PrivateSocketChannels,
    },

    computed: mapGetters({
      token: 'authToken',
      authCheck: 'authCheck',
      socketId: 'getSocketId'
    }),

    watch: {
      authCheck: function () {
        this.initSocket();
      }
    },

    methods: {
      initSocket: function () {
        if (window.Echo) {
          window.Echo.disconnect();
        }

        let authHeaders = {};

        if (this.user) {
          authHeaders = {
            headers: {
              Authorization: 'Bearer ' + this.token
            },
          };
        }

        const echoOptions = {
          broadcaster: 'socket.io',
          host: window.config.socketUrl,
          auth: authHeaders,
        };

        window.Echo = new Echo(echoOptions);

        //Let's update the socketId after connection succeeds. This will let other components to watch socketId and subscribe to necessary channels
        let self = this;
        window.Echo.connector.socket.on('connect', function () {
          self.$store.dispatch('setSocketId', {socketId: window.Echo.socketId()})
        });
      }
    },

    mounted() {
      let self = this;
      setTimeout(function () {
        //If socket is not connected in authCheck watch, connect it.
        if (!self.socketId) {
          self.initSocket();
        }
      }, 2000);

    }
  }
</script>