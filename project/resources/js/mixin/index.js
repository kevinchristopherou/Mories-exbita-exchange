import {mapGetters, mapActions} from 'vuex';
import swal from 'sweetalert2';
import axios from 'axios';

const mixin = {
  data: function () {
    return {
      vue_paginate_class_sm: {
        'ul': ['pagination', 'pagination-sm'],
        'li': 'page-item',
        'a': 'page-link'
      },
      datepickerOptions: {
        format: 'yyyy-MM-dd',
        language: window.config.locale,
      },
      sitesettings: window.config.site,
      locales: window.config.locales
    }
  },
  methods: {
    ... mapActions(['setLocale']),
    getChangeColorClass(change) {
      if (change > 0)
        return 'color-green';
      else if (change < 0)
        return 'color-red';
      else
        return 'color-blue';
    },
    /*
     * Number Formatting Functions
     */
    formatDecimal(value, decimals) {
      if (value === '' && value === null) {
        let val = 0;
        return val.toFixed(decimals);
      }

      if (typeof this.value === 'string')
        value = value.replace(',', '.');

      let reg = RegExp('^\\d*(\\.\\d*)?$');
      if (!reg.test(value)) {
        let val = 0;
        return val.toFixed(decimals);
      }

      return this.$options.filters.round(value, decimals);
    },

    trunc(value, decimals) {
      if (!value) {
        value = 0;
      }

      if (!decimals) {
        decimals = 0;
      }

      value = (Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals)).toFixed(decimals);
      return value;
    },
    /*
     * Toast Functions
     */
    toastSuccess(text) {
      if (text === '') {
        text = this.$t('operation_success');
      }
      this.$toasted.show(text, {
        icon: 'check',
        type: 'success',
        duration: 10000
      });
    },
    toastError(text) {
      if (text === '') {
        text = this.$t('operation_failed');
      }
      this.$toasted.show(text, {
        icon: 'times-circle',
        type: 'error',
        duration: 10000
      });
    },
    toastInfo(text) {
      this.$toasted.show(text, {
        icon: 'info-circle',
        type: 'info',
        duration: 10000
      });
    },
    toastWarning(text) {
      this.$toasted.show(text, {
        icon: 'warning',
        type: 'warning',
        duration: 10000
      });
    },
    toastLaravelError(e) {
      if ('response' in e && 'data' in e.response && 'errors' in e.response.data) {
        let errorString = '';
        _.each(e.response.data.errors, (error) => {
          errorString += error.join('<br>');
        });

        this.toastError(errorString);
      } else {
        this.toastError('');
      }
    },
    /*
     * Sweet Alert Functions
     */
    swalConfirm(title = "") {
      return swal({
        title: title ? title : this.$t('confirm_action'),
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: this.$t('confirm_action_yes'),
        cancelButtonText: this.$t('confirm_action_cancel'),
        focusConfirm: false,
      });
    },
    /*
     * Order Functions
     */
    //returns the brief information of the given order as text
    getOrderBrief(order) {
      let m = this.$store.getters.getMarketById(order.market_id);

      let translateArgs = {
        cryptoSymbol: m.currency,
        fiatSymbol: m.fiat_currency,
        market: m.name,
        quantity: this.$options.filters.round(order.quantity, 8),
        rate: this.$options.filters.round(order.rate, 2)
      };

      let translationKey = '';
      if (order.type === 'BUY') {
        translationKey = 'order.buy_brief';
      }
      else if (order.type === 'BUY_LIMIT') {
        translationKey = 'order.buy_limit_brief';
      }
      else if (order.type === 'SELL') {
        translationKey = 'order.sell_brief';
      }
      else if (order.type === 'SELL_LIMIT') {
        translationKey = 'order.sell_limit_brief';
      } else {
        return;
      }
      return this.$t(translationKey, translateArgs);
    },
    //returns the cancel tooltip text of the given order
    getCancelTooltipText: function (order) {
      return this.$t('order_book.cancel_tooltip', {uuid: order.uuid});
    },
    //returns human readable type of the order
    getOrderTypeText: function (order) {
      if (order.type == "BUY_LIMIT")
        return this.$t('market.transaction_buy');
      else
        return this.$t('market.transaction_sell');
    },
    //
    //cancels the given order
    //
    cancelOrder: function (order) {
      let uuid = order.uuid;
      this.swalConfirm(this.$t('market.order_cancel_confirm', {uuid})).then(() => {
        axios.post('order/cancel', {uuid}).then(() => {
          this.toastSuccess(this.$t('market.order_cancel_success', {uuid}));
        }).catch(() => {
          this.toastError(this.$t('market.order_cancel_fail', {uuid}));
        });
      }).catch((e) => {
        console.log(e);
      });
    },
    //returns the market currency symbol of the given order
    getOrderMarketCurrency: function (order) {
      return this.$store.getters.getMarketByName(order.market).currency;
    },
    //returns the market fiat currency symbol of the given order
    getOrderMarketQuoteCurrency: function (order) {
      return this.$store.getters.getMarketByName(order.market).quote_currency;
    },
    //returns the market currency symbol of the given order
    getOrderMarketCurrencyDecimals: function (order) {
      return this.$store.getters.getMarketByName(order.market).currency_format_decimals;
    },
    //returns the market fiat currency symbol of the given order
    getOrderMarketQuoteCurrencyDecimals: function (order) {
      return this.$store.getters.getMarketByName(order.market).quote_currency_format_decimals;
    },
    //
    //GLOBAL LOGOUT FUNCTION
    //
    async logout() {
      // Log out the user.
      await this.$store.dispatch('logout');
      this.toastSuccess(this.$t('logout_successful'));

      let nextRoute = 'markets';
      if (this.$router.currentRoute.name === 'welcome') {
        nextRoute = 'welcome'
      }

      this.$router.push({name: nextRoute});
    },
    //
    //SLEEP FUNCTION
    sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    },
  },
  computed: {
    //We may need user and authenticated props from auth store in every vue component,
    //So let's define them as computed properties by default.
    ...mapGetters({
      user: 'authUser',
      authenticated: 'authCheck',
      getLocale: 'getLocale'
    }),
  }
};

export default mixin;