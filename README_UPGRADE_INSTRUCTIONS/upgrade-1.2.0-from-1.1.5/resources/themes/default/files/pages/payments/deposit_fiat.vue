<template src="./deposit_fiat.htm"></template>

<script>
  import Form from 'vform'
  import moment from 'moment-timezone'
  import axios from 'axios'
  import swal from 'sweetalert2'

  export default {

    data: function () {
      return {
        selectedCurrency: null,
        depositDescription: '',
        waiting: false,
        success: false,
        form: new Form({
          amount: '',
          fiat_currency_id: '',
          description: '',
          isValidated: false,
        }),
        busy: false,
        fiatCurrencies: {},
        deposits: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        offset: 1
      }
    },

    computed: {
      isWaiting: function () {
        return this.waiting;
      },
      amountPlaceholder: function () {
        if (!this.selectedCurrency)
          return '';
        return this.$t('deposit.min_amount') + ': ' + this.$options.filters.round(this.selectedCurrency.deposit_min, 2) + ' ' + this.selectedCurrency.symbol;
      }
    },

    mounted: function () {
      if (!this.user.id_verified) {
        this.$router.push({name: 'settings.id_verification'})
      }
    },

    created: function () {
      this.getCurrencies();
      this.loadData();
      this.setDescription(this.getRandom(10));
    },

    methods: {
      confirmCheck() {
        if (!this.form.isValidated) {
          this.createDeposit();
        } else {
          this.confirmModal();
        }
      },
      confirmModal() {

        swal({
          title: this.$t('confirm_action'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('confirm_action_yes'),
          cancelButtonText: this.$t('confirm_action_cancel'),
        }).then((result) => {

          this.createDeposit();

        }).catch(swal.noop);
      },
      async getCurrencies() {
        try {
          let response = await axios.get('/currencies/deposit/fiat');
          this.setFiatCurrencies(response.data.data);
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async loadData({page: page = 1} = {}) {
        try {
          let response = await axios.get('/deposit/fiat?perpage=' + this.deposits.meta.per_page + '&page=' + page);
          this.deposits = response.data
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async createDeposit() {
        try {
          this.busy = true;
          let response = await this.form.post('/deposit/fiat');

          if (response.data.validated) {
            this.setValidated(true);
            this.confirmModal();
          }
          else if (response.data.success) {
            this.waiting = true;
            this.loadData();
          }

        } catch (e) {
          this.error = e.response.data.message;
        }

        this.busy = false;
      },
      getNote(deposit) {

        if (deposit.note) {
          return deposit.note;
        }

        return false;
      },
      refreshDeposit() {
        this.setValidated(false);
        this.setAmount('');
        this.setFiatCurrency('');
        this.setDescription(this.getRandom(10));
        this.waiting = false;
        this.selectedCurrency = null;
      },
      setAmount(value) {
        this.form.amount = value;
      },
      setDescription(value) {
        this.form.description = value;
      },
      setFiatCurrencies(value) {
        this.fiatCurrencies = value;
      },
      setFiatCurrency(value) {
        this.form.fiat_currency_id = value;
      },
      setValidated(boolean) {
        this.form.isValidated = boolean;
      },
      getRandom(length) {
        return Math.floor(Math.pow(10, length - 1) + Math.random() * 9 * Math.pow(10, length - 1))
      },
      setSelectedCurrency(e) {

        this.form.clear();
        this.setValidated(false);

        if (e.target.options.selectedIndex == 0) {
          this.selectedCurrency = null;
        } else {
          this.selectedCurrency = this.fiatCurrencies[e.target.options.selectedIndex - 1];

          try {
            let desc = JSON.parse(this.selectedCurrency.deposit_description);
            this.depositDescription = desc[this.$store.getters.getLocale];
          }
          catch (e) {
            console.log(e);
            this.depositDescription = false;
          }

          // this.setAmount(this.selectedCurrency.deposit_min);
        }
      },
      formChange() {
        this.setValidated(false);
      }
    },
    filters: {
      moment: function (date) {
        return moment(date).format('DD.MM.YYYY');
      }
    }
  }
</script>
