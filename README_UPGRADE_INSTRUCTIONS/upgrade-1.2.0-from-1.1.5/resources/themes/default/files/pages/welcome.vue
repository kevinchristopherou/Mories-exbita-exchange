<template src="./welcome.htm"></template>

<script>
  import navMobile from './welcome/nav-mobile';
  import navDesktop from './welcome/nav-desktop';
  import market from './welcome/market';
  import features from './welcome/features';
  import currencies from './welcome/currencies';
  import welcomeFooter from './welcome/footer';

  export default {
    layout: 'default',

    components: {
      navMobile,
      navDesktop,
      market,
      features,
      currencies,
      welcomeFooter,
    },

    metaInfo() {
      return {title: this.$t('home')}
    },

    data: () => ({
      title: window.config.appName,
    }),

    mounted() {
      $("body").scrollspy({
        target: "#welcome-navbar"
      });
    }

  }
</script>
