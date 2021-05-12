<template src="./email.htm"></template>

<script>
  import Form from 'vform'

  export default {
    layout: 'singleform',
    metaInfo() {
      return {title: this.$t('reset_password')}
    },

    data: () => ({
      error: '',
      success: '',
      form: new Form({
        email: '',
        g_recaptcha_response: ''
      }),
      acceptLock: false,
      acceptError: false,
      recaptchaError: [],
    }),

    methods: {
      setRecaptchaResponse(payload) {
        this.form.g_recaptcha_response = payload.g_recaptcha_response;
      },
      async send() {

        if (!this.acceptLock) {
          this.acceptError = true;
          return;
        }

        this.recaptchaError = [];
        this.acceptError = false;

        this.error = '';
        this.success = '';
        try {
          await this.form.post('/user/forgotpassword')
          this.success = "password_reset_link_sent";
          this.form.reset();
        }
        catch (e) {
          this.success = '';
          if (this.form.errors && this.form.errors.errors && this.form.errors.errors.g_recaptcha_response) {
            this.recaptchaError = this.form.errors.errors.g_recaptcha_response;
          }
          this.error = e.response.data.message;
          window.grecaptcha.reset();
        }
      }
    }
  }
</script>
