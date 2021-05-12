<template>
  <div class="app-layout">
    <navbar v-if="selectedTheme == 'default'"></navbar>
    <topbar v-if="selectedTheme == 'default'"></topbar>
    <dark-light-topbar v-if="selectedTheme == 'dark-light'"></dark-light-topbar>
    <dark-light-navbar v-if="selectedTheme == 'dark-light'"></dark-light-navbar>
    <section id="secure-main" :class="extendedMain">
      <child/>
    </section>
    <bottombar v-if="selectedTheme == 'default'"></bottombar>
    <dark-light-bottombar v-if="selectedTheme == 'dark-light'"></dark-light-bottombar>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Topbar from '~/components/Topbar'
import Bottombar from '~/components/Bottombar'
import DarkLightTopbar from '~/components/dark-light/DarkLightTopbar'
import DarkLightNavbar from '~/components/dark-light/DarkLightNavbar'
import DarkLightBottombar from '~/components/dark-light/DarkLightBottombar'

export default {
  name: 'market',
  data() {
    return {
      selectedTheme: 'default',
      extendedMain: '',
    }
  },

  components: {
    Topbar,
    Navbar,
    Bottombar,
    DarkLightTopbar,
    DarkLightBottombar,
    DarkLightNavbar
  },

  watch:{
    $route (to, from){
        // this.selectedTheme
        const delaySpeed = /market\//.test(to.fullPath) ? 300 : 0;

        setTimeout(()=>{
          const path = window.location.hash.replace('/', '').replace('#', '');
          this.selectedTheme = window.config.appTheme;

          localStorage.setItem('active-theme', this.selectedTheme);

          let checkSinglePath = /market\//.test(path);

          if(checkSinglePath && this.selectedTheme == 'dark-light') {
            this.extendedMain = 'secure-no-padding';
          }

          if(!checkSinglePath && this.selectedTheme == 'dark-light') {
            this.selectedTheme = 'default';
            this.extendedMain = '';
          }
        },delaySpeed);
    }
  },

  mounted() {
    const path = window.location.hash.replace('/', '').replace('#', '');
    this.selectedTheme = window.config.appTheme;

    localStorage.setItem('active-theme', this.selectedTheme);

    let checkSinglePath = /market\//.test(path);

    if(checkSinglePath && this.selectedTheme == 'dark-light') {
      this.extendedMain = 'secure-no-padding';
    }

    if(!checkSinglePath && this.selectedTheme == 'dark-light') {
      this.selectedTheme = 'default';
      this.extendedMain = '';
    }
  },
}
</script>