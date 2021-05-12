<template src="./referral.htm"></template>

<script>
import axios from "axios";

export default {
  data: () => ({
    refcode: '',
    plainRef: '',
    referrals: {},
    earnings: {
      data: {},
      meta: {
        last_page: 1,
        total: 0,
        per_page: 20,
        current_page: 1
      }
    },
    offset: 1
  }),
  mounted() {
    this.fetchRefCode();
    this.getReferrals();
    this.getEarnings();
  },
  methods: {
    async fetchRefCode() {
      try {
        let {data} = await axios.get('refcode');
        this.plainRef = data;
        this.refcode = this.generateUrl();
      } catch (e) {

      }
    },
    async getReferrals() {
      try {
        let {data} = await axios.get('referrals');
        this.referrals = data;
      } catch (e) {

      }
    },
    async getEarnings({page: page = 1} = {}) {
      try {
        let response = await axios.get('referralearnings?perpage=' + this.earnings.meta.per_page + '&page=' + page);
        this.earnings = response.data
      } catch (e) {
        this.error = e.response.data.message;
      }
    },
    generateUrl() {
      return window.location.href.replace('settings/referral', 'register') + '?ref=' + this.plainRef;
    },
    copyCode() {
      document.getElementById("refcode").select();
      document.execCommand("Copy");
      this.toastSuccess(this.$t('deposit_copy_success'))
    }
  },

}
</script>
