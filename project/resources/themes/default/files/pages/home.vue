<template src="./home.htm"></template>

<script>
  import OpenOrders from "./my-orders/open-orders";

  export default {
    layout: 'market',

    components: {
      'open-orders': OpenOrders,
    },

    metaInfo() {
      return {title: this.$t('home')}
    },

    data: () => ({
      fiat_currency_id: window.config.mainFiatCurrency.id,
      fiat_symbol: window.config.mainFiatCurrency.symbol
    }),

    beforeRouteEnter(to, from, next) {
      next(vm => {
        if (!vm.user) {
          vm.$router.push({name: 'markets'})
        }
      });
    },

    computed: {
      wallets: function () {
        let wallets = _.filter(this.$store.getters.getWallets, (item) => {
          return item.total_balance > 0;
        });

        return _.map(wallets, (wallet) => {

          let market = this.getWalletMarket(wallet);

          let rate = market ? market.last : 0;
          return {
            'marketName': market ? market.name : null,
            'symbol': wallet.currency,
            'rate': rate,
            'available': wallet.available,
            'pending': wallet.pending,
            'withdraw_pending': wallet.withdraw_pending,
            'total': wallet.total_balance,
            'fiatBalance': rate * wallet.total_balance,
          }
        })
      },

      fiatWallet: function () {
        let wallet = this.$store.getters.getFiatWalletByCurrency(this.fiat_currency_id);

        if (!wallet) {
          return wallet;
        }

        return {
          'symbol': wallet.currency,
          'rate': '-',
          'available': wallet.available,
          'pending': wallet.pending,
          'withdraw_pending': wallet.withdraw_pending,
          'total': wallet.total_balance,
          'fiatBalance': wallet.total_balance,
        }
      }
    },
    methods: {
      getWalletMarket: function (wallet) {
        return this.$store.getters.getMarketByCurrencyId(wallet.currency_id, this.fiat_currency_id);
      },
    }
  }
</script>
