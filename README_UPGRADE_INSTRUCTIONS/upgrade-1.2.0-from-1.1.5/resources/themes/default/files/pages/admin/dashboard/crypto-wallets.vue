<template>
  <div class="card dashboard-card">
    <div class="card-header text-left">
      <strong>{{ $t('admin.dashboard.crypto_wallets_status') }}</strong>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm table-bordered table-hover table-striped mb-0">
        <thead>
        <tr>
          <th>{{ $t('admin.currencies.name') }}</th>
          <th>{{ $t('admin.currencies.online_balance') }}</th>
          <th>{{ $t('admin.currencies.withdraw_pending') }}</th>
          <th>{{ $t('admin.currencies.difference') }}</th>
          <th>{{ $t('admin.currencies.coverage_ratio') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="currency in currencies">
          <td>{{ currency.currency }}</td>
          <td class="text-right">{{ currency.account_balance }}</td>
          <td>
            <span class="float-left">({{currency.withdraw_pending_count}} {{$t('admin.currencies.pieces')}})</span>
            <span class="float-right">{{ currency.withdraw_pending }}</span>
          </td>
          <td class="text-right">
              <span :class="getDiffClass(currency)">
                {{ currency.account_balance - currency.withdraw_pending }}
              </span>
          </td>
          <td class="text-right">
              <span v-if="!getDiffClass(currency)">
                N/A
              </span>
            <span :class="getDiffClass(currency)" v-if="getDiffClass(currency)">
                {{ getCoverageRatio(currency) | round(2)}} %
              </span>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import Vue from 'vue';

  export default {

    data: function () {
      return {
        currencies: [],
      }
    },

    mounted: function () {
      this.loadData();
      this.$bus.$on('UpdateDashboardCurrency', this.updateCurrency);
    },
    beforeDestroy: function () {
      this.$bus.$off('UpdateDashboardCurrency');
    },
    methods: {
      async loadData() {
        try {
          let url = 'currencies';
          let response = await axios.get(url);
          this.currencies = response.data.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      updateCurrency(currency) {
        _.each(this.currencies, (c, index) => {
          if (currency.id === c.id) {
            Vue.set(this.currencies, index, currency);
          }
        })
      },

      getCoverageRatio(currency) {

        if (currency.withdraw_pending === 0) {
          return null;
        }
        return (100 * (currency.account_balance - currency.withdraw_pending)) / currency.withdraw_pending;
      },

      getDiffClass(currency) {
        let ratio = this.getCoverageRatio(currency);

        if (ratio === null) {
          return '';
        }
        if (ratio < 100) {
          return 'text-danger';
        } else if (ratio < 200) {
          return 'text-warning';
        } else {
          return 'text-success';
        }
      }
    }
  }
</script>