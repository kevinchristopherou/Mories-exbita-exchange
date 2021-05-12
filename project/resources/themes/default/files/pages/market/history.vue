<template src="./history.htm"></template>

<script>

  export default {
    props: ['market'],

    data: function () {
      return {
        perPage: 10,
        currentPage: 1,
        fields: [
          {
            key: 'dateField',
            label: this.$t('market.transaction_time')
          },
          {
            key: 'typeField',
            label: this.$t('market.transaction_type')
          },
          {
            key: 'amountField',
            label: this.$t('market.transaction_amount') + ' [' + this.market.currency + ']'
          },
          {
            key: 'rateField',
            label: this.$t('market.transaction_rate') + ' [' + this.market.quote_currency + ']'
          },
          {
            key: 'totalField',
            label: this.$t('market.transaction_total') + ' [' + this.market.quote_currency + ']'
          }
        ]
      }
    },

    computed: {
      history: function () {
        return this.$store.getters.getMarketHistory(this.market.name);
      },
    },

    methods: {}

  }
</script>