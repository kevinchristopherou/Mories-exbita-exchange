<template src="./orderbook.htm"></template>

<script>
  import * as _ from 'lodash';
  import axios from 'axios';
  import QuickBuy from "./quick-buy";
  import QuickSell from "./quick-sell";
  import LimitBuy from "./limit-buy";
  import LimitSell from "./limit-sell";

  export default {
    components: {
        'quick-buy': QuickBuy,
        'quick-sell': QuickSell,
        'limit-buy': LimitBuy,
        'limit-sell': LimitSell,
    },

    props: {
      market: {required: true},
      theme: {required: false},
      chartUrl: {required: false},
      wallet: {required: false},
      quoteWallet: {required: false},
    },

    data: function () {
      return {
        perPage: 10,
        sellOrdersCurrentPage: 1,
        buyOrdersCurrentPage: 1,
        sellOrderFields: [
          {
            key: 'actions',
            thStyle: {
              width: '60px'
            }
          },
          {
            key: 'rateField',
            label: this.$t('order_book.ask_price') + ' [' + this.market.quote_currency + ']'
          },
          {
            key: 'quantityField',
            label: this.$t('order_book.amount') + ' [' + this.market.currency + ']'
          },
          {
            key: 'totalField',
            label: this.$t('order_book.total') + ' [' + this.market.quote_currency + ']'
          },
          {
            key: 'sumField',
            label: this.$t('order_book.sum') + ' [' + this.market.quote_currency + ']'
          }
        ],
        buyOrderFields: [
          {
            key: 'actions',
            thStyle: {
              width: '60px'
            }
          },
          {
            key: 'rateField',
            label: this.$t('order_book.bid_price') + ' [' + this.market.quote_currency + ']'
          },
          {
            key: 'quantityField',
            label: this.$t('order_book.amount') + ' [' + this.market.currency + ']'
          },
          {
            key: 'totalField',
            label: this.$t('order_book.total') + ' [' + this.market.quote_currency + ']'
          },
          {
            key: 'sumField',
            label: this.$t('order_book.sum') + ' [' + this.market.quote_currency + ']'
          }
        ],
        tableWidth: $('.table-sell-2').innerWidth(), 
        selectedTheme: ''
      }
    },

    computed: {
      sellOrders: function () {
        return this.$store.getters.getMarketOrderBook(this.market.name, 'sell');
      },
      buyOrders: function () {
        return this.$store.getters.getMarketOrderBook(this.market.name, 'buy');
      }
      ,
      sellOrdersTotal: function () {
        let orders = this.$store.getters.getMarketOrderBook(this.market.name, 'sell');
        return _.sumBy(orders, 'quantity');
      },
      buyOrdersTotal: function () {
        let orders = this.$store.getters.getMarketOrderBook(this.market.name, 'buy');
        let last = _.last(orders);
        return last ? last.sum : 0;
      },
      sellOrdersTotalFiat: function () {
        let orders = this.$store.getters.getMarketOrderBook(this.market.name, 'sell');
        let last = _.last(orders);
        return last ? last.sum : 0;
      }
    },

    methods: {
      getQbarWidthSell: function (sum) {
        let ratio = (sum / this.sellOrdersTotalFiat);

        let w = this.tableWidth * ratio;
        return w;
      },
      getQbarWidthBuy: function (sum) {
        let ratio = (sum / this.buyOrdersTotal);
        let w = this.tableWidth * ratio;
        return w;
      },
      getRowTooltipText: function (order) {
        let text = "Bu fiyattan " + order.quantities.length + " emir mevcut: <br>";
        for (let x in order.quantities) {
          text += this.$options.filters.round(order.quantities[x], 8) + "<br>";
        }

        return text;
      },

      getTableCancelTooltipText: function (order) {
        return this.$t('order_book.table_cancel_tooltip', {count: order.uuids.length});
      },

      cancelOrders: function (uuids) {
        this.swalConfirm(this.$t('market.orders_cancel_confirm')).then(() => {
          _.each(uuids, (uuid) => {
            axios.post('order/cancel', {uuid}).then(() => {
              this.toastSuccess(this.$t('market.order_cancel_success', {uuid}));
            }).catch(() => {
              this.toastError(this.$t('market.order_cancel_fail', {uuid}));
            });
          });
        }).catch(() => {
        });
      },
      handleResize(event) {
        this.tableWidth = $('.table-sell-2').innerWidth();
      }
    },
    mounted() {
      this.handleResize();
      window.addEventListener('resize', this.handleResize);

      if(this.theme) {
        this.selectedTheme = this.theme;
      }
    },
    watch: {
      theme: function(val) {
        if(val) {
          this.selectedTheme = val;
        }
      },
      buyOrders: function(val) {
        setTimeout(()=>{
          this.handleResize();
        },3000);
      },
      sellOrders: function(val) {
        setTimeout(()=>{
          this.handleResize();
        },3000);
      }
    },
    beforeDestroy: function () {
      window.removeEventListener('resize', this.handleResize)
    },

  }
</script>