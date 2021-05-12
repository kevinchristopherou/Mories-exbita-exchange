<template>
  <div class="app-layout">
    <admin-navbar></admin-navbar>
    <section id="secure-main" class="secure-main-container">
      <child/>
    </section>
    <admin-socket-channels></admin-socket-channels>
  </div>
</template>

<script>
  import AdminNavbar from '~/components/AdminNavbar'
  import AdminSocketChannels from '../../services/AdminSocketChannels'

  export default {
    layout: 'default',
    components: {
      AdminNavbar,
      AdminSocketChannels
    },

    metaInfo() {
      return {title: this.$t('home')}
    }
  }
</script>
