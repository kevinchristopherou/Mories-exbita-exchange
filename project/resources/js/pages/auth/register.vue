<template src="./register.htm"></template>

<script>
  import Vue from "vue"
  import swal from "sweetalert2"
  import {Form} from 'vform'

  export default {

    layout: 'singleform',
    metaInfo() {
      return {title: this.$t('register')}
    },

    data: () => ({
      form: new Form({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        g_recaptcha_response: '',
        refcode: '',
      }),
      agreeTerms: false,
      agreeError: false,
      recaptchaError: [],
      registerFailed: false,
      registerComplete: false,
      sendingVerfication: false,
      verficationComplete: false,
      verficationFailed: false,
      showReferral: false,
    }),

    mounted() {
      let refcode = this.$route.query.ref;
      if(refcode)
        this.form.refcode = refcode;
    },

    methods: {
      setRecaptchaResponse(payload) {
        this.form.g_recaptcha_response = payload.g_recaptcha_response;
      },

      async register() {

        if (!this.agreeTerms) {
          this.agreeError = true;
          return;
        }

        this.recaptchaError = [];
        this.agreeError = false;

        try {
          await this.form.post('/user/register');
          this.registerComplete = true;
          this.registerFailed = false;
        } catch (e) {
          //Extract recaptcha error if exists
          if (this.form.errors && this.form.errors.errors && this.form.errors.errors.g_recaptcha_response) {
            this.recaptchaError = this.form.errors.errors.g_recaptcha_response;
          }
          this.registerFailed = true;
          this.registerComplete = false;
        }

        window.grecaptcha.reset();
      },
      showTerms() {
        swal({
          html: '<terms-content></terms-content>',
          showCancelButton: false,
          width: '80%',
        });

        new Vue({
          el: swal.getContent()
        })
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
      },
      toggleReferral() {
        this.showReferral = !this.showReferral
      }
    }
  }
</script>
