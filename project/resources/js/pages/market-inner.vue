<template src="./market-inner.htm"></template>

  <script>
    import axios from 'axios';
    import QuickBuy from "./market/quick-buy";
    import QuickSell from "./market/quick-sell";
    import LimitBuy from "./market/limit-buy";
    import LimitSell from "./market/limit-sell";
    import Orderbook from "./market/orderbook";
    import MyOrders from "./market/my-orders";
    import History from "./market/history";
    import MyHistory from "./market/my-history";
    import {mapGetters} from 'vuex'

    export default {
      components: {
        MyHistory,
        'my-orders': MyOrders,
        'orderbook': Orderbook,
        'history': History,
        'quick-buy': QuickBuy,
        'quick-sell': QuickSell,
        'limit-buy': LimitBuy,
        'limit-sell': LimitSell,
        'my-history': MyHistory
      },

      metaInfo() {
        return {title: this.$route.params.marketName + ' ' + this.$t('market.marketplace')}
      },

      data: function () {
        return {
          marketName: this.$route.params.marketName,
          quickSellAmount: 0,
          limitSellRate: 0,
          limitSellAmount: 0,
          costFactor: window.config.tradeCostFactor,
          commissionFactor: window.config.tradeCommissionFactor,
          taxFactor: window.config.tradeTaxFactor,
          selectedTheme: ''
        }
      },
      watch: {
        market: function (val, oldVal) {
          if (!oldVal && val) {
            this.setMarket(val.name);
          } else if (oldVal && val && val.name !== oldVal.name) {
            this.unsubscribeFromSocket(oldVal.name);
            this.setMarket(val.name);
            this.subscribeToSocket(val.name);
          }
        },
        socketId: function (val, oldVal) {
          this.subscribeToSocket(this.market.name);
        },
        $route (to, from){
          // this.selectedTheme
          const path = window.location.hash.replace('/', '').replace('#', '');
          this.selectedTheme = window.config.appTheme;

          let checkSinglePath = /market\//.test(path);
          if(!checkSinglePath && this.selectedTheme == 'dark-light') {
            this.selectedTheme = 'default';
          }

          if( document.readyState !== 'loading' ) {
              document.getElementsByTagName('body')[0].classList.add('single-market-page');
          } else {
              document.addEventListener('DOMContentLoaded', function () {
                  document.getElementsByTagName('body')[0].classList.add('single-market-page');
              });
          }
        }
      },
      computed: {
        ...mapGetters({
          markets: 'getMarkets'
        }),
        chartUrl: function () {
          let params = {
            'market': this.marketName,
            'locale': this.$store.getters.getLocale,
          };

          return '/chart/?' + jQuery.param(params);
        },
        socketId: function () {
          return this.$store.getters.getSocketId;
        },
        market: function () {
          return this.$store.getters.getMarketByName(this.marketName);
        },
        quoteWallet: function () {
          if(this.market.quote_currency_type == 1)
            return this.$store.getters.getWalletByCurrency(this.market.quote_currency_id)

          return this.$store.getters.getFiatWalletByCurrency(this.market.quote_currency_id)
        },
        wallet: function () {
          if(this.market.currency_type == 1)
            return this.$store.getters.getWalletByCurrency(this.market.currency_id)

          return this.$store.getters.getFiatWalletByCurrency(this.market.currency_id)
        },
      },

      mounted() {
        // this.selectedTheme
        const path = window.location.hash.replace('/', '').replace('#', '');
        this.selectedTheme = window.config.appTheme;

        let checkSinglePath = /market\//.test(path);

        if(!checkSinglePath && this.selectedTheme == 'dark-light') {
          this.selectedTheme = 'default';
        }

        if( document.readyState !== 'loading' ) {
            document.getElementsByTagName('body')[0].classList.add('single-market-page');
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementsByTagName('body')[0].classList.add('single-market-page');
            });
        }
      },

      methods: {
        async setMarket(name) {
          this.marketName = name;
          let response = await axios.get('market/' + name + '/orderbook/both');
          let orders = response.data.data;
          this.$store.dispatch('setMarketOrders', {market: this.market, orders});

          response = await axios.get('market/' + name + '/history');
          let history = response.data.data;
          this.$store.dispatch('setMarketHistory', {market: this.market, history});
        },
        unsubscribeFromSocket() {
          window.Echo.leave('orderbook-' + this.market.name);
          this.$store.dispatch('setMarketOrders', {market: this.market, orders: []});
          this.$store.dispatch('setMarketHistory', {market: this.market, history: []});
        },
        subscribeToSocket(name) {
          // console.log('subscribeToSocket');
          if (this.$store.getters.getSocketId !== null) {
            let channelName = 'orderbook-' + name;
            if (channelName in window.Echo.connector.channels) {
              // console.log("already subscribed to channel, no need to re-subscribe");
              return;
            }
            let self = this;
            window.Echo.channel(channelName)
              .listen('OrderBookUpdated', (e) => {
                // console.log("SOCKET DATA RECEIVED:", e);
                if (e.action === "cancel" || e.action === "processed") {
                  self.$store.dispatch('removeMarketOrder', {market: this.market, order: e.order});
                } else {
                  self.$store.dispatch('setMarketOrder', {market: this.market, order: e.order});
                }
              })
              .listen('MarketHistoryUpdated', (e) => {
                self.$store.dispatch('pushMarketHistory', {market: this.market, transaction: e.t});
              });
          }
        },
        getBackgroundColor(index) {
          if(index == 0) {
            return 1;
          }
          return index % 7;
        },
        getMarketInitials(name) {
          return name[0];
        }
      },
      beforeRouteEnter(to, from, next) {
        next(vm => {
          let m = vm.$store.getters.getMarketByName(to.params.marketName);
          if (m) {
            vm.setMarket(m.name);
            vm.subscribeToSocket(m.name);
          }
        })
      },
      beforeRouteLeave(to, from, next) {
        this.unsubscribeFromSocket(from.params.marketName);
        if(document.getElementsByTagName('body')[0].classList.contains('single-market-page')) {
          document.getElementsByTagName('body')[0].classList.remove('single-market-page');
        }
        next();
      },
      beforeRouteUpdate(to, from, next) {
        let m = this.$store.getters.getMarketByName(to.params.marketName);
        if (m) {
          this.unsubscribeFromSocket(from.params.marketName);
          this.setMarket(m.name);
          this.subscribeToSocket(m.name);
          next();
        }
      },
    }
  </script>
