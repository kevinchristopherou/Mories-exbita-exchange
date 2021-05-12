<template src="./TwoFaVerifyForm.htm"></template>

<script>
  import {mapGetters, mapActions} from 'vuex'

  export default {
    name: 'two-fa-verify-form',
    data: () => {
      return {
        otp: this.setOtp,
        smsBusy: false
      }
    },
    props: {
      action: {
        type: String,
        default: 'verify'
      },
      verifyError: {
        default: null
      },
      buttonLabel: {
        type: String,
        default: 'login'
      },
      buttonHide: {
        type: Boolean,
        default: false
      },
      showLogout: {
        type: Boolean,
        default: false
      },
      showAbort: {
        type: Boolean,
        default: false
      },
      method: {
        type: String,
        default: null
      },
      phone: {
        type: String,
        default: ''
      },
      setOtp: {
        type: String,
        default: '',
      },
      busy: {
        type: Boolean,
        default: false
      },
      smsTrigger: {
        type: String,
        default: 'auto'
      }
    },

    computed: {
      ...mapGetters({
        smsSent: 'smsSent',
        smsTimeout: 'smsTimeout',
        smsRequestError: 'smsRequestError',
        smsShowResend: 'smsShowResend'
      })
    },
    methods: {
      ...mapActions(['sendSms']),
      emitOtp() {
        this.$emit('otpchange', {otp: this.otp})
      },
      verify() {
        this.$emit('verify', {otp: this.otp})
      },
      abort() {
        this.$emit('abort')
      },
      async resendSms() {
        this.smsBusy = true;
        if (this.action === 'verify') {
          await this.sendSms();
        } else if (this.action === 'setup') {
          await this.sendSms({setup: true, phone: this.phone});
        }
        this.smsBusy = false;
      },
      cleanOtp() {
        this.otp = '';
      },
      filterOtp(evt) {

        if (this.method == "sms")
          return true;

        evt = (evt) ? evt : window.event;

        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
          evt.preventDefault();
        } else {
          return true;
        }

      }
    }
  }
</script>
