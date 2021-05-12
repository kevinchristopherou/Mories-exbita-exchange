<template>
  <div class="app-layout">
    <navbar></navbar>
    <topbar></topbar>
    <section id="secure-main">
      <child/>
    </section>
    <bottombar></bottombar>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Topbar from '~/components/Topbar'
import Bottombar from '~/components/Bottombar'

export default {
  name: 'market',

  components: {
    Topbar,
    Navbar,
    Bottombar
  },

  mounted() {
    this.selectedTheme = window.config.appTheme;

    localStorage.setItem('active-theme', this.selectedTheme);
  }
}
</script>