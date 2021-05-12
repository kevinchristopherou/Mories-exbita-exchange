<template src="./my-history.htm"></template>

<script>
  import axios from 'axios';

  export default {

    data: function () {
      return {
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
            key: 'amountFilledField',
            label: this.$t('order.amount_filled')
          }
        ],
      }
    },

    props: {
      market: {
        type: Object,
        default: null
      },
      marketName: {
        type: String,
        default: ''
      },
      theme: {
        required: false,
        type: String,
        default: ''
      }
    },

    mounted() {
      this.loadOrders();
    },
    watch: {
      marketName: function (newVal, oldVal) {
        this.loadOrders();
      },
      myorders: function () {
        this.loadOrders();
      }
    },
    computed: {
      myorders: function () {
        return this.$store.getters.getMyOrdersByMarket(this.marketName);
      },
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
      },
      loadOrders: function () {
        let url = 'orders?status=filled&perpage=' + this.orders.meta.per_page + '&page=' + this.orders.meta.current_page + '&market=' + this.market.name;

        axios.get(url).then((response) => {
          this.orders = response.data;
        });

      }
    }

  }
</script>