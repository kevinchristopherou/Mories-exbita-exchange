<template src="./ipverify.htm"></template>

<script>
  import axios from 'axios'

  export default {
    layout: 'singleform',

    metaInfo() {
      return {title: this.$t('login_ip_verification')}
    },

    data: () => ({
      status: 'verifying',
      error: '',
      ip: ''
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
          let response = await axios.get('/user/' + this.userId + '/verifyip/' + this.token);
          this.status = 'success';
          this.ip = response.data.ip;
        } catch (e) {
          this.status = 'failed';
          this.error = e.response.data.message;
        }
      }
    }
  }
</script>
