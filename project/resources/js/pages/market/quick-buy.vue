<template src="./quick-buy.htm"></template>

<script>
  import axios from 'axios';

  export default {
    name: 'quick-buy',

    props: ['market', 'wallet', 'quoteWallet'],

    data: function () {
      return {
        busy: false,
        quickBuyAmount: 0,
        costFactor: window.config.tradeCostFactor,
        commissionFactor: window.config.tradeCommissionFactor,
        taxFactor: window.config.tradeTaxFactor
      }
    },

    computed: {
      quickBuyApproximate: function () {
        return (this.quickBuyAmount / this.costFactor) / this.market.ask;
      },
      quickBuyHelpText: function () {
        let amount = this.quickBuyAmount;
        let rawAmount = amount / this.costFactor;
        let commission = this.$options.filters.round(rawAmount * this.commissionFactor, 2);
        let tax = this.$options.filters.round(rawAmount * this.taxFactor, 2);
        let quoteSymbol = this.market.quote_currency;
        let cryptoSymbol = this.market.currency;
        rawAmount = this.$options.filters.round(rawAmount, 2);

        return this.$t('market.quick_buy_helptext', {
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
      quickBuyMax() {
        this.quickBuyAmount = parseFloat(this.quoteWallet.available);
      },
      async quickBuy() {
        if (this.quickBuyAmount == 0) {
          return;
        }

        this.busy = true;
        try {
          let {data} = await axios.post('order/buy', {
            market: this.market.name,
            quantity: this.quickBuyAmount
          });

          this.toastSuccess(this.getOrderBrief(data.order) + ' ' + this.$t('order.created'));
          this.quickBuyAmount = 0;
        } catch (e) {
          this.toastLaravelError(e);
        }

        this.busy = false;
      }
    }

  }
</script>