<template>
  <div>
    <main class="whitebox" id="settings-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.ethereum') }} {{ $t('admin.site_settings.settings') }}</h3>

      <site-settings
          keys="ETHEREUM_USER,ETHEREUM_PASSWORD,ETHEREUM_HOST,ETHEREUM_PORT,ETHEREUM_CONFIRMATIONS_REQUIRED,ETHEREUM_TRANSACTION_URL_LOOKUP"></site-settings>
    </main>

    <main class="mt-5 whitebox" id="bitcoin-node-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.ethereum-status') }}</h3>

      <!-- Submit Button -->
      <div class="form-group text-center">
        <button class="btn btn-big btn-warning" v-on:click="getEthereumNodeStatus">{{
            $t('admin.site_settings.ethereum-status-button') }}
        </button>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios'
import SiteSettings from "./site-settings";

export default {
  components: {
    SiteSettings,
    'site-settings': SiteSettings
  },
  methods: {
    async getEthereumNodeStatus() {
      try {
        let response = await axios.get('/ethereum-node-status');
        if(response.data.status == true) {
          if(parseFloat(response.data.progress) >= 99.99) {
            this.toastSuccess(this.$t('admin.site_settings.ethereum-status-ok'));
          } else {
            this.toastInfo(this.$t('admin.site_settings.ethereum-status-progress') + ' ' + response.data.progress + '%');
          }
        } else {
          this.toastWarning(this.$t('admin.site_settings.ethereum-status-error'));
        }
      } catch (e) {
        this.toastError('');
      }
    },
  }
}
</script>