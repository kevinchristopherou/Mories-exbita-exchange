<template src="./wallets.htm"></template>

<script>
import axios from "axios";

export default {
  metaInfo() {
    return {title: this.$t('my_orders.title')}
  },

  data: function () {
    return {
      wallets: {},
      fiatWallets: {}
    }
  },
  mounted() {
    this.getFiatWallets();
    this.getWallets();
  },
  methods: {
    async getWallets() {
      try {
        let {data} = await axios.get('wallets');
        this.wallets = data;
      } catch (e) {
        console.log(e);
      }
    },
    async getFiatWallets() {
      try {
        let {data} = await axios.get('fiatwallets');
        this.fiatWallets = data
      } catch (e) {
        console.log(e);
      }
    },
  }
}
</script>
