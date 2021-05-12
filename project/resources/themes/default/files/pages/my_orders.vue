<template src="./my_orders.htm"></template>

<script>

  import OpenOrders from "./my-orders/open-orders";
  import FilledOrders from "./my-orders/filled-orders";
  // import CanceledOrders from "./my-orders/canceled-orders";

  export default {

    components: {
      'open-orders': OpenOrders,
      'filled-orders': FilledOrders,
      // 'canceled-orders': CanceledOrders,
    },

    metaInfo() {
      return {title: this.$t('my_orders.title')}
    },

    data: function () {
      return {}
    },
    methods: {}
  }
</script>