<template src="./my-orders.htm"></template>

<script>
  export default {
    props: ['market', 'theme'],

    data: function () {
      return {
        perPage: 5,
        currentPage: 1,
        fields: [
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
          },
          {
            key: 'actions',
            thStyle: {
              width: '60px'
            }
          }
        ]
      }
    },

    computed: {
      myorders: function () {
        return this.$store.getters.getMyOrdersByMarket(this.market.name);
      }
    },

    methods: {
      formatDateTime(value) {
        if(!value) {
          return '-';
        }
        let date = moment(value);
        let dateformated = date.format('DD/MM/YYYY HH:mm:ss');
        let partDate = dateformated.split(' ');
        return partDate[0] + ' <span>' + partDate[1] + '</span>';
      }
    }

  }
</script>