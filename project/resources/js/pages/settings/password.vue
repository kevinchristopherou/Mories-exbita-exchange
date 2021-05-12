<template src="./password.htm"></template>

<script>
  import Form from 'vform'
  import { mapGetters, mapActions} from 'vuex'
  import store from '~/store'

  export default {
    data: () => ({
      form: new Form({
        current_password: '',
        password: '',
        password_confirmation: '',
        otp: '',
      }),
      success: false,
      error: false,
      verifyError: null,
      twoFaMethod: false,
      twoFaShow: false,
    }),

    methods: {
      async update() {
        this.success = false;
        this.error = false;
        this.verifyError = null;

        try {
          await this.form.put('/user/changepassword')
          this.success = true;
          this.form.reset();

          if(this.twoFaMethod) {
            this.cleanOtp();
          }
        }
        catch (e) {

          this.error = e.response.data.message;

          if(this.error == "two_fa_incorrect_code" || this.error == "two_fa_incorrect_length")
          {
            this.verifyError = this.error;
          }
        }
      },
      ...mapActions(['sendSms']),
      cleanOtp() {
        this.$refs.twoFa.cleanOtp();
      },
      setOtp({otp}) {
        this.form.otp = otp;
      },
    },
    computed: {
      ...mapGetters({
        twoFa: 'twoFaError',
        smsSent: 'smsSent',
        smsTimeout: 'smsTimeout',
        smsRequestError: 'smsRequestError'
      })
    },
    mounted() {
      if (store.getters.authUser.two_fa_enabled) {
        this.twoFaMethod = store.getters.authUser.two_fa_method;

        if (this.twoFaMethod === 'sms') {
          this.sendSms();
        }
      }
    },
  }
</script>
