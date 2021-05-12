<template src="./quick-sell.htm"></template>

<script>
  import axios from 'axios';

  export default {
    name: 'quick-sell',

    props: ['market', 'wallet', 'quoteWallet', 'theme'],

    data: function () {
      return {
        busy: false,
        quickSellAmount: 0,
        costFactor: window.config.tradeCostFactor,
        commissionFactor: window.config.tradeCommissionFactor,
        taxFactor: window.config.tradeTaxFactor
      }
    },

    computed: {
      quickSellApproximate: function () {
        let rawAmount = this.quickSellAmount * this.market.bid;
        let commission = "";
        let tax = this.$options.filters.round(rawAmount * this.taxFactor, 7);
        return rawAmount - commission - tax;
      },
      quickSellHelpText: function () {
        let amount = this.quickSellApproximate;
        let rawAmount = this.quickSellAmount * this.market.bid;
        let commission = this.$options.filters.round(rawAmount * this.commissionFactor, 2);
        let tax = this.$options.filters.round(rawAmount * this.taxFactor, 2);
        let quoteSymbol = this.market.quote_currency;
        let cryptoSymbol = this.market.currency;
        rawAmount = this.$options.filters.round(rawAmount, 2);
        amount = this.$options.filters.round(amount, 2);

        return this.$t('market.quick_sell_helptext', {
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
      quickSellMax: function () {
        this.quickSellAmount = parseFloat(this.wallet.available);
      }
      ,
      async quickSell() {
        if (this.quickSellAmount == 0) {
          return;
        }

        this.busy = true;
        try {
          let {data} = await axios.post('order/sell', {
            market: this.market.name,
            quantity: this.quickSellAmount
          });

          this.toastSuccess(this.getOrderBrief(data.order) + ' ' + this.$t('order.created'));
          this.quickSellAmount = 0;
        } catch (e) {
          this.toastLaravelError(e);
        }

        this.busy = false;
      }
    }

  }
</script>