<template>
  <div>
    <main class="whitebox" id="settings-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.coinpayments') }} {{ $t('admin.site_settings.settings') }}</h3>

      <site-settings
          keys="COINPAYMENTS_MERCHANT_ID,COINPAYMENTS_PRIVATE_KEY,COINPAYMENTS_PUBLIC_KEY,COINPAYMENTS_IPN,COINPAYMENTS_DEPOSIT_FEE"></site-settings>
    </main>

    <main class="mt-5 whitebox" id="bitcoin-node-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.coinpayments-status') }}</h3>

      <!-- Submit Button -->
      <div class="form-group text-center">
        <button class="btn btn-big btn-warning" v-on:click="getCoinpaymentsStatus">{{
            $t('admin.site_settings.coinpayments-status-button') }}
        </button>

        <button class="btn btn-big btn-success ml-5" v-on:click="syncCoinpaymentsCoins">{{
            $t('admin.site_settings.coinpayments-sync-button') }}
        </button>
      </div>
    </main>
    <main class="mt-5 whitebox" id="coinpayments-coins-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.exchange_settings.currencies.available')}}</h3>
        <table class="table table-responsive">
          <thead>
          <tr>
            <th>{{ $t('admin.site_settings.coin_name') }}</th>
            <th>{{ $t('admin.site_settings.coin_symbol') }}</th>
            <th>{{ $t('admin.site_settings.coin_tx_fee') }}</th>
            <th>{{ $t('admin.site_settings.coin_deposit_fee') }}</th>
            <th>{{ $t('admin.site_settings.coin_status') }}</th>
            <th>{{ $t('admin.site_settings.coin_confirmations') }}</th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="coinpaymentsCurrencies" v-for="coin in coinpaymentsCurrencies">
            <td>{{ coin.name}}</td>
            <td>{{ coin.symbol}}</td>
            <td>{{ coin.tx_fee}}</td>
            <td>0.5%</td>
            <td>{{ coin.status}}</td>
            <td>{{ coin.confirms}}</td>
          </tr>
          </tbody>
        </table>
    </main>
  </div>
</template>

<script>
import axios from 'axios'
import SiteSettings from "./site-settings";

export default {
  data: function () {
    return {
      coinpaymentsCurrencies: {}
    }
  },
  components: {
    SiteSettings,
    'site-settings': SiteSettings
  },
  mounted() {
    this.getCoinpaymentsCoins();
  },
  methods: {
    async getCoinpaymentsStatus() {
      try {
        let response = await axios.get('/coinpayments-status');
        if(response.data.status == true) {
            this.toastSuccess(this.$t('admin.site_settings.coinpayments-status-ok'));
        } else {
          this.toastWarning(this.$t('admin.site_settings.coinpayments-status-error'));
        }
      } catch (e) {
        this.toastError('');
      }
    },
    async syncCoinpaymentsCoins() {
      try {
        let response = await axios.get('/coinpayments-sync');
        if(response.data.status == true) {
          this.toastSuccess(this.$t('admin.site_settings.coinpayments-sync-ok'));
          this.getCoinpaymentsCoins();
        } else {
          this.toastWarning(this.$t('admin.site_settings.coinpayments-status-error'));
        }
      } catch (e) {
        this.toastError('');
      }
    },
    async getCoinpaymentsCoins() {
      try {
        let response = await axios.get('/settings/coinpayments-currencies');
        this.coinpaymentsCurrencies = response.data.data;
      } catch (e) {
        this.toastError('');
      }
    },
  }
}
</script>
