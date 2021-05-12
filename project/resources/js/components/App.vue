<template>
  <div id="app">
    <loading ref="loading"></loading>

    <transition name="page" mode="out-in">
      <component v-if="layout" :is="layout"></component>
    </transition>

    <socket></socket>

  </div>
</template>

<script>
  import Loading from './Loading'
  import Socket from './Socket'

  // Load layout components dynamically.
  const requireContext = require.context('../layouts', false, /.*\.vue$/)

  const layouts = requireContext.keys()
    .map(file =>
      [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
    )
    .reduce((components, [name, component]) => {
      components[name] = component.default
      return components
    }, {})

  export default {
    el: '#app',

    components: {
      Loading,
      Socket
    },

    metaInfo() {
      const {appName} = window.config

      return {
        title: appName,
        titleTemplate: `%s · ${appName}`
      }
    },

    data: () => ({
      layout: null,
      defaultLayout: 'market'
    }),

    mounted() {
      this.$loading = this.$refs.loading;
      this.$store.dispatch('initTokenCheckInterval');
      this.$store.dispatch('loadMarkets');
      if (this.user) {
        this.$store.dispatch('loadOrders');
      }

    },
    watch: {
      user: async function (newVal, oldVal) {
        //Watch for vuex store user change and load/clear orders accordingly.
        if (!newVal && oldVal) {
          this.$store.dispatch('clearOrders');
        } else if (newVal && !oldVal) {
          this.$store.dispatch('loadOrders');
        }
      }
    },

    methods: {
      /**
       * Set the application layout.
       *
       * @param {String} layout
       */
      setLayout(layout) {
        // console.log('App.vue set layout', layout);
        if (!layout || !layouts[layout]) {
          layout = this.defaultLayout
        }

        this.layout = layouts[layout]
      },

      /**
       * Used to redirect to user to homepage if he's authenticated, or to welcome page if he's not authenticated. Can be used within a route component
       * this.$router.app.redirectFallBack();
       */
      redirectFallBack() {
        if (this.$store.getters.authCheck) {
          this.$router.push({name: 'markets'});
        } else {
          this.$router.push({name: 'welcome'});
        }
      }
    }
  }
</script>
