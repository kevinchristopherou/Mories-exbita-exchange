<template src="./open-orders.htm"></template>

<script>
  export default {

    data: function () {
      return {
        perPage: 10,
        currentPage: 1,
        marketName: null,
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
            key: 'typeField',
            label: this.$t('order.order_type')
          },
          {
            key: 'rateField',
            label: this.$t('order.rate')
          },
          {
            key: 'quantityField',
            label: this.$t('order_book.amount')
          },
          {
            key: 'quantityRemainingField',
            label: this.$t('order_book.amount_remaining')
          },
          {
            key: 'actions',
            thStyle: {
              width: '60px'
            }
          }
        ],
      }
    },

    computed: {
      myorders: function () {
        return this.$store.getters.getMyOrdersByMarket(this.marketName);
      },
      markets: function () {
        return this.$store.getters.getMarkets;
      }
    },

    methods: {}

  }
</script>