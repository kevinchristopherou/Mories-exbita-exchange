<template src="./DarkLightNavbar.htm"></template>

<script>
  import {mapGetters} from 'vuex'

  export default {
    data: () => ({
      appName: window.config.appName,
      pageAd: window.config.ad,
      //we don't want 'admin' word to be searchable in the bundled js code for non-admin site. Use shifting encryption
      adTitle: 'Knwsx'.split('').map(c => String.fromCharCode(c.charCodeAt() - 10)).join('')
    }),

    computed: mapGetters({
      isAd: 'adCheck'
    }),

    methods: {
      isActive(prefix) {
        if (!this.$route.name) {
          return false;
        }

        return this.$route.name.startsWith(prefix);
      },

      hideMenu() {
        if(document.getElementById('secure-header').style.display == 'none') {
            document.getElementById('secure-header').style.display = '';
        } else {
            document.getElementById('secure-header').style.display = 'none';
        }
      }
    }
  }
</script>