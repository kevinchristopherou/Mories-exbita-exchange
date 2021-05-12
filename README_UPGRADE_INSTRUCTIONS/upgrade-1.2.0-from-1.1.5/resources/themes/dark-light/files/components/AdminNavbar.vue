<template src="./AdminNavbar.htm"></template>
<script>

  export default {
    data: () => ({
      appName: window.config.appName
    }),

    methods: {
      isActive(prefix) {
        if (!this.$route.name) {
          return false;
        }

        return this.$route.name.startsWith(prefix);
      }
    }
  }
</script>
