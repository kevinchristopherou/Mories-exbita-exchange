<template src="./filled-orders.htm"></template>

<script>
  import axios from 'axios';

  export default {

    data: function () {
      return {
        marketName: null,
        orders: {
          data: [],
          meta: {
            total: 0,
            per_page: 10,
            from: 0,
            to: 0,
            current_page: 1,
            last_page: 1
          }
        },
        fields: [
          {
            key: 'marketField',
            label: this.$t('market.market')
          },
          {
            key: 'dateField',
            label: this.$t('order.created_date')
          },
          {
            key: 'filledDateField',
            label: this.$t('order.filled_date')
          },
          {
            key: 'typeField',
            label: this.$t('order.order_type')
          },
          {
            key: 'rateField',
            label: this.$t('order.rate')
          },
          {
            key: 'rateActualField',
            label: this.$t('order.rate_actual')
          },
          {
            key: 'quantityField',
            label: this.$t('order_book.amount')
          },
          {
            key: 'amountFilledField',
            label: this.$t('order.amount_filled')
          }
        ],
      }
    },

    mounted() {
      this.loadOrders();
    },
    computed: {
      markets: function () {
        return this.$store.getters.getMarkets;
      },
    },
    watch: {
      marketName: function (newVal, oldVal) {
        this.loadOrders();
      },
    },
    methods: {
      loadOrders: function () {
        let url = 'orders?status=filled&perpage=' + this.orders.meta.per_page + '&page=' + this.orders.meta.current_page;
        if (this.marketName !== '') {
          url += '&market=' + this.marketName;
        }

        axios.get(url).then((response) => {
          this.orders = response.data;
        });
      }
    }

  }
</script>