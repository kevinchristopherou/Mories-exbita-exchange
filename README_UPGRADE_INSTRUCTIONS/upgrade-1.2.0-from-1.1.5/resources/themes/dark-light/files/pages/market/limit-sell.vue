<template src="./limit-sell.htm"></template>

<script>
  import axios from 'axios';

  export default {
    props: ['market', 'wallet', 'quoteWallet', 'theme'],

    data: function () {
      return {
        busy: false,
        limitSellRate: 0,
        limitSellAmount: 0,
        costFactor: window.config.tradeCostFactor,
        commissionFactor: window.config.tradeCommissionFactor,
        taxFactor: window.config.tradeTaxFactor
      }
    },

    computed: {
      limitSellCost: function () {
        let rawAmount = this.limitSellAmount * this.limitSellRate;
        let commission = this.$options.filters.round(rawAmount * this.commissionFactor, 7);
        let tax = this.$options.filters.round(rawAmount * this.taxFactor, 7);
        return rawAmount - commission - tax;
      },

      limitSellHelpText: function () {
        let amount = this.limitSellCost;
        let rawAmount = this.limitSellAmount * this.limitSellRate;
        let commission = this.$options.filters.round(rawAmount * this.commissionFactor, 2);
        let tax = this.$options.filters.round(rawAmount * this.taxFactor, 2);
        let quoteSymbol = this.market.quote_currency;
        let cryptoSymbol = this.market.currency;
        rawAmount = this.$options.filters.round(rawAmount, 2);
        amount = this.$options.filters.round(amount, 2);

        return this.$t('market.limit_sell_helptext', {
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
      limitSellMax: function () {
        if (!this.limitSellRate || parseInt(this.limitSellRate.toString()) === 0) {
          this.limitSellRate = parseFloat(this.market.last);
        }

        this.limitSellAmount = parseFloat(this.wallet.available);
      },
      async limitSell() {
        if (this.limitSellAmount == 0 || this.limitSellRate == 0) {
          return;
        }

        this.busy = true;
        try {
          let {data} = await axios.post('order/selllimit', {
            market: this.market.name,
            quantity: this.limitSellAmount,
            rate: this.limitSellRate
          });

          this.toastSuccess(this.getOrderBrief(data.order) + ' ' + this.$t('order.created'));

          this.limitSellAmount = 0;
          this.limitSellRate = 0;
        } catch (e) {
          this.toastLaravelError(e);
        }
        this.busy = false;
      }
    }

  }
</script>