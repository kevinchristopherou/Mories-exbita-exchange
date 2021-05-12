<template src="./limit-buy.htm"></template>

<script>
  import axios from 'axios';

  export default {
    props: ['market', 'wallet', 'quoteWallet', 'theme'],

    data: function () {
      return {
        busy: false,
        limitBuyRate: 0,
        limitBuyAmount: 0,
        costFactor: window.config.tradeCostFactor,
        commissionFactor: window.config.tradeCommissionFactor,
        taxFactor: window.config.tradeTaxFactor
      }
    },

    computed: {
      limitBuyCost: function () {
        return (this.limitBuyAmount * this.limitBuyRate) * this.costFactor;
      },

      limitBuyHelpText: function () {
        let amount = this.limitBuyCost;
        let rawAmount = amount / this.costFactor;
        let commission = this.$options.filters.round(rawAmount * this.commissionFactor, 2);
        let tax = this.$options.filters.round(rawAmount * this.taxFactor, 2);
        let quoteSymbol = this.market.quote_currency;
        let cryptoSymbol = this.market.currency;
        rawAmount = this.$options.filters.round(rawAmount, 2);

        return this.$t('market.limit_buy_helptext', {
          amount,
          rawAmount,
          commission,
          tax,
          quoteSymbol,
          cryptoSymbol
        });
      },
    },

    methods: {
      limitBuyMax: function () {
        if (!this.limitBuyRate || parseInt(this.limitBuyRate.toString()) === 0) {
          this.limitBuyRate = parseFloat(this.market.last);
        }

        //Max out the buy amount according to the total available balance - fees
        let totalAmount = parseFloat(this.quoteWallet.available);
        let rawAmount = totalAmount / this.costFactor;

        this.limitBuyAmount = this.trunc(rawAmount / this.limitBuyRate,8);
      }
      ,
      async limitBuy() {
        if (this.limitBuyAmount == 0 || this.limitBuyRate == 0) {
          return;
        }

        this.busy = true;
        try {
          let {data} = await axios.post('order/buylimit', {
            market: this.market.name,
            quantity: this.limitBuyAmount,
            rate: this.limitBuyRate
          });

          this.toastSuccess(this.getOrderBrief(data.order) + ' ' + this.$t('order.created'));

          this.limitBuyAmount = 0;
          this.limitBuyRate = 0;
        } catch (e) {
          this.toastLaravelError(e);
        }
        this.busy = false;
      }
    }

  }
</script>