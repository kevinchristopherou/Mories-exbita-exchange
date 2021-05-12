<template src="./two_fa_setup.htm"></template>

<script>
  import {Form, HasError, AlertError} from 'vform'
  import axios from 'axios'
  import {mapGetters, mapActions} from 'vuex'

  export default {

    computed: {
      ...mapGetters({
        smsSent: 'smsSent',
        smsRequestError: 'smsRequestError'
      })
    },

    data: () => ({
      'method': null,
      'gaSetupData': null,
      'phone': '',
      'busy': false,
      'verifyError': null,
      'cancelState': 'none',
      'smsSetupState': 'none'
    }),

    methods: {
      ...mapActions(['sendSms', 'smsReset']),
      //SETUP METHDOS
      async setupBegin(method) {
        if (method === 'ga') {
          this.method = 'ga';
          try {
            let response = await axios.get('/twofa/setup/ga');
            this.gaSetupData = response.data;
          } catch (e) {
            this.error = e.response.data.message;
          }
        }
        if (method === 'sms') {
          this.method = 'sms';
        }
      },

      async setupSms() {
        this.busy = true;
        try {
          await this.sendSms({setup: true, phone: this.phone});
          this.smsSetupState = 'phoneSet';
        } catch (e) {
        }

        this.busy = false;
      },

      async setupVerify({otp}) {
        this.busy = true;
        try {
          await axios.get('/twofa/setupcomplete/' + otp);
          this.$store.dispatch('fetchUser');
          this.reset();
        } catch (e) {
          this.verifyError = e.response.data.message;
        }

        this.busy = false;
      },

      reset() {
        this.smsReset();
        this.busy = false;
        this.method = null;
        this.gaSetupData = null;
        this.verifyError = null;
        this.phone = '';
        this.cancelState = 'none';
        this.smsSetupState = 'none';
      },

      // CANCEL METHODS
      async cancelBegin() {
        this.cancelState = 'verify'
        if (this.user.two_fa_method === 'sms') {
          await this.sendSms();
        }
      },

      //This sends the verify code to api to cancel 2fa setup
      async cancelVerify({otp}) {
        this.busy = true;
        try {
          await axios.post('/twofa/cancel', {otp: otp})
          this.$store.dispatch('fetchUser');
          this.smsReset();
          this.cancelState = 'none';
          this.method = null;
        }
        catch (e) {
          this.verifyError = e.response.data.message
        }
        this.busy = false;
      },
    },

    beforeRouteLeave(to, from, next) {
      this.$store.dispatch('smsReset');
      next();
    }
  }
</script>
