<template src="./markets.htm"></template>

  <script>
    import {mapGetters} from 'vuex'

    export default {
      metaInfo() {
        return {title: this.$t('market.marketplace')}
      },

      data: function () {
        return {}
      },
      computed: {
        ...mapGetters({
          markets: 'getMarkets'
        }),
      },

      methods: {}
    }
  </script>
