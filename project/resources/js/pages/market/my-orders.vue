<template src="./my-orders.htm"></template>

<script>
  export default {
    props: ['market'],

    data: function () {
      return {
        perPage: 5,
        currentPage: 1,
        fields: [
          {
            key: 'actions',
            thStyle: {
              width: '60px'
            }
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
            label: this.$t('order.rate') + ' [' + this.market.quote_currency + ']'
          },
          {
            key: 'quantityField',
            label: this.$t('order_book.amount') + ' [' + this.market.currency + ']'
          },
          {
            key: 'quantityRemainingField',
            label: this.$t('order_book.amount_remaining') + ' [' + this.market.currency + ']'
          },
          {
            key: 'totalField',
            label: this.$t('order_book.total') + ' [' + this.market.quote_currency + ']'
          }
        ]
      }
    },

    computed: {
      myorders: function () {
        return this.$store.getters.getMyOrdersByMarket(this.market.name);
      }
    },

    methods: {}

  }
</script>