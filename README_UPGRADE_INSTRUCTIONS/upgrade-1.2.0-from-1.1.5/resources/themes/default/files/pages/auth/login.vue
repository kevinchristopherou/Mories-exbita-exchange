<template src="./login.htm"></template>

<script>
  import Form from 'vform'
  import axios from 'axios'
  import {mapGetters} from 'vuex'

  export default {
    layout: 'singleform',

    metaInfo() {
      return {title: this.$t('login')}
    },

    mounted() {
      this.getCaptchaStatus();
    },

    data: () => ({
      form: new Form({
        email: '',
        password: ''
      }),
      remember: false,
      error: '',
      showRecaptcha: false,
      recaptchaError: [],
      sendingVerfication: false,
      verficationComplete: false,
      verficationFailed: false,
    }),

    computed: {
      ...mapGetters(['authTwoFaRequired'])
    },

    methods: {
      async getCaptchaStatus() {
        let response = await axios.get('/user/lcs');
        this.showRecaptcha = response.data.required;
      },

      setRecaptchaResponse(payload) {
        this.form.g_recaptcha_response = payload.g_recaptcha_response;
      },

      async login() {

        //reset the errors first
        this.recaptchaError = [];
        this.error = '';

        // Submit the form.
        let data = null;
        try {
          const response = await this.form.post('/user/login');
          data = response.data;
        } catch (e) {
          this.error = e.response.data.message;

          if (this.error !== 'server_error') {
            this.error = 'login_' + this.error;
          }

          //Extract recaptcha error if exists
          if (this.form.errors && this.form.errors.errors && this.form.errors.errors.g_recaptcha_response) {
            this.recaptchaError = this.form.errors.errors.g_recaptcha_response;
          }
          //Update captcha status
          this.getCaptchaStatus();
          if ($("#input_recaptcha").length > 0) {
            window.grecaptcha.reset();
          }

          return;
        }

        // Save the token.
        this.$store.dispatch('saveToken', {
          token: data,
          remember: this.remember
        });

        // Fetch the user.
        await this.$store.dispatch('fetchUser');


        //If fetch user fails due to 2FA, redirect to two fa page
        if (this.authTwoFaRequired) {
          this.$router.push({name: 'two_fa'})
        }
        // Otherwise redirect to home
        else {
          this.$router.push({name: 'home'})
        }
      },

      async resendVerification() {
        this.verficationComplete = false;
        this.verficationFailed = false;
        try {
          this.sendingVerfication = true;
          await this.form.post('/user/resend/emailverification');
          this.sendingVerfication = false;
          this.verficationComplete = true;
          this.verficationFailed = false;
        } catch (e) {
          this.sendingVerfication = false;
          this.verficationComplete = false;
          this.verficationFailed = true;
        }
      }
    }
  }
</script>
