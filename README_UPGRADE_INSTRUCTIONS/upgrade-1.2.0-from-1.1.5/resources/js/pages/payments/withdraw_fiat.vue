<template src="./withdraw_fiat.htm"></template>

<script>
  import Form from 'vform'
  import {mapGetters} from 'vuex'
  import axios from 'axios'
  import moment from 'moment-timezone'
  import store from '~/store'
  import swal from 'sweetalert2'

  export default {

    data: function () {
      return {
        waiting: false,
        success: false,
        twoFaMethod: false,
        twoFaShow: false,
        verifyError: null,
        form: new Form({
          currency: 0,
          amount: '',
          bank_name: '',
          bank_account: '',
          recipient: '',
          iban: '',
          otp: '',
          isValidated: false,
        }),
        busy: false,
        withdrawals: {
          data: [],
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        offset: 1,
        //remember vars
        rememberedAccounts: [],
        forceDontRemember: false,
        rememberName: '',
      }
    },

    computed: {
      isWaiting: function () {
        return this.waiting;
      },
      ...mapGetters({
        twoFa: 'twoFaError',
        smsSent: 'smsSent',
        smsTimeout: 'smsTimeout',
        smsRequestError: 'smsRequestError',
        fiatWallets: 'getFiatWallets'
      }),
      showRemember: function () {
        if (this.forceDontRemember) {
          return false;
        }
        for (let x in this.rememberedAccounts) {
          if (this.rememberedAccounts[x].iban === this.form.iban) {
            return false;
          }
        }

        return true;
      }
    },

    methods: {
      rememberedAccountsByCurrency(fiat_currency_id) {
        if (fiat_currency_id == 0) {
          return [];
        }
        return _.filter(this.rememberedAccounts, (account) => {
          return account.fiat_currency_id === fiat_currency_id
        });
      },
      confirmCheck() {
        if (!this.form.isValidated) {
          this.createWithdrawal();
        } else {
          this.confirmModal();
        }
      },
      cleanOtp() {
        this.$refs.twoFa.cleanOtp();
      },
      prepareOtp(otp) {
        this.setOtp(otp);
        this.setValidated(false);
      },
      setOtp({otp}) {
        this.form.otp = otp;
      },
      confirmModal() {

        swal({
          title: this.$t('confirm_action'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('confirm_action_yes'),
          cancelButtonText: this.$t('confirm_action_cancel'),
        }).then((result) => {
          this.createWithdrawal();
        }).catch(swal.noop);
      },
      async loadData({page: page = 1} = {}) {
        try {
          let url = 'withdraw/fiat/?perpage=' + this.withdrawals.meta.per_page + '&page=' + page;

          let response = await axios.get(url);
          this.withdrawals = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },

      async getRemembered() {
        try {
          let response = await axios.get('/withdraw/fiat/remembered');
          this.rememberedAccounts = response.data;
        }
        catch (e) {
          this.error = e.response.data.message;
        }
      },
      async createWithdrawal() {
        try {

          this.verifyError = false;
          this.busy = true;
          if (this.form.isValidated) {
            let response = await this.form.post('/withdraw/fiat');
            if (response.data.success) {
              this.waiting = true;
              this.loadData();
            }
          } else {
            let response = await this.form.post('/withdraw/validate/fiat');
            if (response.data.validated) {
              this.setValidated(true);
              this.confirmModal();
            }
          }
        } catch (e) {
          this.error = e.response.data.message;

          if (this.error == "two_fa_incorrect_code") {
            this.verifyError = this.error;
          }
        }

        this.busy = false;
      },
      getNote(withdraw) {

        if (withdraw.note) {
          return withdraw.note;
        }

        return false;
      },
      refreshWithdrawal() {
        this.setValidated(false);
        this.setAmount('');
        this.setIban('');
        this.setBank('');
        this.setAccount('');
        this.setCurrency(0);
        this.verifyError = null,
          this.waiting = false;

        if (this.twoFaMethod) {
          this.cleanOtp();
        }
        this.forceDontRemember = false;
        this.remember_name = '';
      },
      setAmount(value) {
        this.form.amount = value;
      },
      setCurrency(value) {
        this.form.currency = value;
      },
      setBank(value) {
        this.form.bank_name = value;
      },
      setAccount(value) {
        this.form.bank_account = value;
      },
      setIban(value) {
        this.form.iban = value;
      },
      setValidated(boolean) {
        this.form.isValidated = boolean;
      },
      setCurrencyAmount(e) {

        this.setValidated(false);

        if (e.target.options.selectedIndex == 0) {
          return false;
        }

        this.form.amount = this.fiatWallets[e.target.options.selectedIndex - 1].available;
      },
      formChange() {
        this.setValidated(false);
      },
      async remember() {
        this.busy = true;
        try {
          let response = await axios.post('/withdraw/fiat/remembered', {
            remember_name: this.rememberName,
            iban: this.form.iban,
            fiat_currency_id: this.form.currency
          });
          this.toastSuccess(this.$t('withdraw.remember_success'));
          this.getRemembered();
          this.dontRemember();
        }
        catch (e) {
          this.toastError('');
        }

        this.busy = false;
      },
      dontRemember() {
        this.forceDontRemember = true;
      },
      useRememberedAcount(account) {
        this.form.bank_name = account.bank_name;
        this.form.iban = account.iban;
        this.form.swift_code = account.swift_code;
      },
      async deleteRememberedAcount(account) {
        this.busy = true;
        try {
          await axios.delete('/withdraw/fiat/remembered/' + account.id);
          this.toastSuccess('');
          this.getRemembered();
        }
        catch (e) {
          this.toastError('');
        }
        this.busy = false;
      }
    },
    filters: {
      moment: function (date) {
        return moment(date).format('DD.MM.YYYY');
      }
    },
    mounted() {

      if (!this.user.id_verified) {
        const kyc_state = window.config.kycState;
        if(kyc_state)
          this.$router.push({name: 'settings.id_verification'})
      }

      if (store.getters.authUser.two_fa_enabled) {
        this.twoFaMethod = store.getters.authUser.two_fa_method;
      }

      this.loadData();
      this.getRemembered();
    },
  }
</script>
