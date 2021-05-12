<template src="./two_fa.htm"></template>

<script>

  import {Form} from 'vform'
  import axios from 'axios'
  import {mapGetters, mapActions} from 'vuex'

  export default {
    layout: 'singleform',
    metaInfo() {
      return {title: this.$t('two_factor_auth')}
    },

    computed: {
      ...mapGetters({
        twoFa: 'twoFaError',
        smsSent: 'smsSent',
        smsTimeout: 'smsTimeout',
        smsRequestError: 'smsRequestError'
      })
    },

    data: () => ({
      method: null,
      verifyError: null,
      errorCount: 0
    }),

    methods: {
      ...mapActions(['sendSms']),
      async tryGoHome() {
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

      async verify({otp}) {
        try {
          await axios.post('/twofa/verify', {otp: otp});
          await this.tryGoHome();
        } catch (e) {
          if (e.response) {
            this.verifyError = e.response.data.message;
          }


          if (e.response.data.message === 'two_fa_incorrect_code') {
            this.errorCount++;

            if (this.errorCount > 2) {
              await this.$store.dispatch('logout');
              this.$router.push({name: 'login'});
            }
          }
        }
      }
    },

    mounted() {
      if (this.twoFa) {
        this.method = this.twoFa.two_fa_method;
        if (this.method === 'sms') {
          this.sendSms();
        }
      } else {
        this.$router.app.redirectFallBack();
      }
    }

  }
</script>
