<template>
  <div>
    <header id="header">
      <nav class="navbar navbar-expand-md navbar-light clearfix" id="welcome-navbar">
        <router-link class="navbar-brand" :to="{name: 'welcome'}">
        	<div v-if="!sitesettings.SITE_LOGO.file_url"><span class="text-400">{{ sitesettings.SITE_NAME.value }}</span></div>
        	<div v-if="sitesettings.SITE_LOGO.file_url"><img :src="sitesettings.SITE_LOGO.file_url" height="50"/></div>
        </router-link>
        <nav-mobile></nav-mobile>
        <nav-desktop></nav-desktop>
      </nav>
    </header>
    <!--CONTENT SLOT-->
    <section>
      <child/>
    </section>
    <!--FOOTER -->
    <welcome-footer></welcome-footer>
  </div>
</template>
<script>
  import welcomeFooter from '../pages/welcome/footer';
  import navMobile from '../pages/welcome/nav-mobile';
  import navDesktop from '../pages/welcome/nav-desktop';
  export default {
    name: 'singleform',
    components: {
      welcomeFooter,
      navMobile,
      navDesktop,
    },
    data: function () {
      return {
        year: (new Date()).getFullYear()
      }
    }
  }
</script>
