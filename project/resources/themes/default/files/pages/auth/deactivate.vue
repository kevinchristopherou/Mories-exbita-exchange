<template src="./deactivate.htm"></template>

<script>
  import axios from 'axios'

  export default {
    layout: 'singleform',

    metaInfo() {
      return {title: this.$t('account_deactivate.title')}
    },

    data: () => ({
      status: '',
      error: '',
      busy: false,
    }),

    computed: {
      userId() {
        return this.$route.params.user_id;
      },
      token() {
        return this.$route.params.token;
      }
    },

    methods: {
      async deactivate() {
        this.busy = true;
        try {
          let response = await axios.get('/user/' + this.userId + '/deactivate/' + this.token);
          this.status = 'success';
        } catch (e) {
          this.status = 'failed';
          this.error = e.response.data.message;
        }

        this.busy = false;
      }
    }
  }
</script>
