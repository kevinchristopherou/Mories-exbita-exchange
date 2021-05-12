<template src="./withdraw_crypto.htm"></template>

<script>
  import Form from 'vform'
  import {mapGetters} from 'vuex'
  import axios from 'axios'
  import store from '~/store'
  import moment from 'moment-timezone'
  import swal from 'sweetalert2'

  export default {

    data: function () {
      return {
        queued: false,
        success: false,
        twoFaMethod: false,
        twoFaShow: false,
        verifyError: null,
        form: new Form({
          currency: 0,
          amount: '',
          address: '',
          otp: '',
          isValidated: false,
        }),
        busy: false,
        currencies: [],
        withdrawals: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        offset: 1,
        fee: 0,
        wallet: null,
        currency: null,
        //remember vars
        rememberedAccounts: [],
        forceDontRemember: false,
        rememberName: '',
      }
    },

    computed: {
      isQueued: function () {
        return this.queued;
      },
      ...mapGetters({
        twoFa: 'twoFaError',
        smsSent: 'smsSent',
        smsTimeout: 'smsTimeout',
        smsRequestError: 'smsRequestError',
        wallets: 'getNonNegativeWallets',
      }),
      showRemember: function () {
        if (this.forceDontRemember) {
          return false;
        }
        for (let x in this.rememberedAccounts) {
          if (this.rememberedAccounts[x].address === this.form.address) {
            return false;
          }
        }

        return true;
      },
      totalAmount: function () {
        return (this.currency.fee_withdraw + parseFloat(this.form.amount));
      }
    },

    methods: {

      //region OTP METHODS
      prepareOtp(otp) {
        this.setOtp(otp);
        this.setValidated(false);
      },
      cleanOtp() {
        this.$refs.twoFa.cleanOtp();
      },
      setOtp({otp}) {
        this.form.otp = otp;
      },
      //endregion

      //region Remember Methods
      rememberedAccountsByCurrency(currency_id) {
        if (currency_id == 0) {
          return [];
        }
        return _.filter(this.rememberedAccounts, (account) => {
          return account.currency_id === currency_id
        });
      },

      async getRemembered() {
        try {
          let response = await axios.get('/withdraw/crypto/remembered');
          this.rememberedAccounts = response.data;
        }
        catch (e) {
          this.error = e.response.data.message;
        }
      },

      async remember() {
        this.busy = true;
        try {
          await axios.post('/withdraw/crypto/remembered', {
            remember_name: this.rememberName,
            address: this.form.address,
            currency_id: this.form.currency
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
        this.form.address = account.address;
      },

      async deleteRememberedAcount(account) {
        this.busy = true;
        try {
          await axios.delete('/withdraw/crypto/remembered/' + account.id);
          this.toastSuccess('');
          this.getRemembered();
        }
        catch (e) {
          this.toastError('');
        }
        this.busy = false;
      },
      //endregion

      //region Submit Methods
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

      async createWithdrawal() {
        try {
          this.verifyError = false;
          this.busy = true;
          if (this.form.isValidated) {
            let response = await this.form.post('/withdraw/crypto');
            if (response.data.success) {
              this.queued = true;
              this.loadData();
            }
          } else {
            let response = await this.form.post('/withdraw/validate/crypto');
            if (response.data.validated) {
              this.setValidated(true);
              this.confirmModal();
            }
          }
        } catch (e) {
          this.verifyError = e.response.data.message;
        }

        this.busy = false;
      },
      async cancel(withdraw) {
        swal({
          title: this.$t('confirm_action'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('confirm_action_yes'),
          cancelButtonText: this.$t('confirm_action_cancel'),
        }).then(async (result) => {
          this.busy = true;
          try {
            await axios.delete('/withdraw/crypto/' + withdraw.id);
            this.toastSuccess();
            this.loadData();
          }
          catch (e) {
            this.toastError('');
          }

          this.busy = false;

        }).catch(swal.noop);
      },
      //endregion

      //region Load Methods
      async loadData({page: page = 1} = {}) {
        try {
          let args = '';
          if (this.currency) {
            args += '&currency_id=' + this.currency.id;
          }
          let response = await axios.get('/withdraw/crypto/?perpage=' + this.withdrawals.meta.per_page + '&page=' + page + args);
          this.withdrawals = response.data
        } catch (e) {
          this.error = e.response.data.message;
        }
      },

      async getCurrencies() {
        try {
          let response = await axios.get('/currencies');
          this.currencies = response.data.data;
        }
        catch (e) {
          this.error = e.response.data.message;
        }
      },

      refreshWithdrawal() {

        this.setValidated(false);

        this.setAddress('');
        this.form.currency = 0;
        this.form.amount = 0;
        this.verifyError = null;
        this.form.clear();
        this.queued = false;

        if (this.twoFaMethod) {
          this.cleanOtp();
        }
        this.forceDontRemember = false;
        this.remember_name = '';
        this.wallet = null;
        this.currency = null
      },
      //endregion

      //region getters setters
      getNote(withdraw) {
        if (withdraw.note) {
          return withdraw.note;
        }
        return false;
      },

      setAddress(value) {
        this.form.address = value;
      },
      setValidated(boolean) {
        this.form.isValidated = boolean;
      },
      setCurrency(e) {
        this.form.clear();
        this.withdrawals.meta.current_page = 1;
        this.setValidated(false);
        if (e.target.options.selectedIndex === 0) {
          this.refreshWithdrawal();
          this.loadData();
          return false;
        }

        this.wallet = this.wallets[e.target.options.selectedIndex - 1];

        this.currency = _.find(this.currencies, (currency) => {
          return currency.id === this.wallet.currency_id;
        });
        //Calculate max amount by subtracting fee
        let amount = this.wallet.available - this.currency.fee_withdraw;
        this.form.amount = this.$options.filters.round(amount, 8);
        this.loadData();
      },
      formChange() {
        this.setValidated(false);
        this.checkAmount();
        this.verifyError = null;
        this.form.clear();
      },
      checkAmount() {
        if (!this.wallet) {
          this.form.amount = 0;
          return;
        }
        if (this.wallet.available < this.currency.fee_withdraw + this.form.amount) {
          let amount = this.wallet.available - this.currency.fee_withdraw;
          this.form.amount = this.$options.filters.round(amount, 8);
        }
      }
      //endregion
    },
    filters: {
      moment: function (date) {
        return moment(date).format('DD.MM.YYYY');
      }
    },
    mounted() {

      if (!this.user.id_verified) {
        this.$router.push({name: 'settings.id_verification'})
      }

      if (store.getters.authUser.two_fa_enabled) {
        this.twoFaMethod = store.getters.authUser.two_fa_method;
      }

      this.getCurrencies();
      this.loadData();
      this.getRemembered();
    },
  }
</script>