<template src="./Topbar.htm"></template>

<script>

    export default {
        data: () => ({
            fiat_currency_id: window.config.mainFiatCurrency.id,
            fiat_symbol: window.config.mainFiatCurrency.symbol
        }),

        computed: {
            topMarkets: function () {
                let m = this.$store.getters.getMarkets.concat([]);

                m.sort(function (a, b) {
                    if (a.volume_24h < b.volume_24h) {
                        return -1
                    }
                    else if (a.volume_24h > b.volume_24h) {
                        return -1
                    }

                    return 0;
                });

                return m.slice(0, 4);
            },

            totalBalance: function () {
                let total = 0;
                _.each(this.$store.getters.getWallets, (item) => {
                    let market = this.getWalletMarket(item);
                    if (market) {
                        total += item.balance * market.last;
                    }
                });

                if (this.fiatWallets[0]) {
                    total += this.fiatWallets[0].balance;
                }

                return total;
            },

            wallets: function () {
                let wallets = _.filter(this.$store.getters.getWallets, (item) => {
                    return item.total_balance > 0;
                });

                return _.map(wallets, (wallet) => {
                    let market = this.getWalletMarket(wallet);
                    let rate = market ? market.last : 0;
                    return {
                        'balance': wallet.total_balance,
                        'symbol': wallet.currency,
                        'rate': rate,
                        'fiatBalance': rate * wallet.total_balance,
                    }
                })
            },

            fiatWallets: function () {
                let wallets = _.filter(this.$store.getters.getFiatWallets, (item) => {
                  return item.total_balance > 0;
                });

                return _.map(wallets, (wallet) => {
                  return {
                    'balance': wallet.total_balance,
                    'symbol': wallet.currency,
                    'rate': '-',
                    'fiatBalance': 0,
                  }
                });
            }
        },
        methods: {
            getWalletMarket: function (wallet) {
                return this.$store.getters.getMarketByCurrencyId(wallet.currency_id, this.fiat_currency_id);
            },
            popoverShow: function (e) {
                let w = window.innerWidth < 420 ? window.innerWidth : 420;
                $(e.relatedTarget).css('max-width', w + 'px');
                $(e.relatedTarget).css('width', w + 'px');

                if (w < 420) {
                    $(e.relatedTarget).css('overflow-y', 'visible');
                    $(e.relatedTarget).css('overflow-x', 'scroll');
                }

                $(e.relatedTarget).css('border-radius', '0px');
                $(e.relatedTarget).css('margin-top', '20px');

                setTimeout(function() {
                    $('#total-balance-dropdown .popover').focus();
                },100);

            }
        }
    }
</script>