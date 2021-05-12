<template src="./reset.htm"></template>

<script>
  import Form from 'vform'

  export default {
    layout: 'singleform',
    metaInfo() {
      return {title: this.$t('reset_password')}
    },

    data: () => ({
      form: new Form({
        id: 0,
        token: '',
        email: '',
        password: '',
        password_confirmation: ''
      }),
      'error': '',
      'success': ''
    }),

    methods: {
      async reset() {
        this.form.token = this.$route.params.token;
        this.form.id = this.$route.params.user_id;

        this.error = '';
        this.success = '';

        try {
          await this.form.post('/user/resetpassword')
          this.success = 'reset_password_success'
        } catch (e) {
          this.error = e.response.data.message;
          return;
        }

        this.form.reset()
      }
    }
  }
</script>
