<template src="./verify.htm"></template>

<script>
  import axios from 'axios'

  export default {
    layout: 'singleform',

    metaInfo() {
      return {title: this.$t('account_verify_title')}
    },

    data: () => ({
      status: 'verifying',
      error: ''
    }),

    computed: {
      userId() {
        return this.$route.params.user_id;
      },
      token() {
        return this.$route.params.token;
      }
    },

    mounted() {
      this.verify();
    },

    methods: {
      async verify() {
        try {
          let response = await axios.get('/user/' + this.userId + '/verify/' + this.token);
          this.status = 'success';
        } catch (e) {
          this.status = 'failed';
          this.error = e.response.data.message;
        }
      }
    }
  }
</script>
