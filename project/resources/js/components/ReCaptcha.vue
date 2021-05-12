<template src="./ReCaptcha.htm"></template>

<script>
  export default {
    name: 're-captcha',

    props: {
      error: {
        type: Array,
        default: []
      },
      form: {
        type: Object,
        default: null
      }
    },
    mounted() {
      this.initReCaptcha();
    },
    computed: {
      errorText: function () {
        return this.error.join('<br>');
      }
    },
    methods: {
      initReCaptcha: function () {
        var self = this;
        setTimeout(function () {
          if (typeof window.grecaptcha === 'undefined') {
            self.initReCaptcha();
          }
          else {
            window.grecaptcha.render('input_recaptcha', {
                sitekey: window.config.recaptchaSiteKey,
                callback: function (response) {
                  self.$emit('setRecaptchaResponse', {g_recaptcha_response: response});
                }
              }
            )
          }
        }, 100);
      }
    }
  }
</script>