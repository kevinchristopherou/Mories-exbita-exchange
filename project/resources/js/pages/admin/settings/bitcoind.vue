<template>
  <div>
    <main class="whitebox" id="settings-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.bitcoind') }} {{ $t('admin.site_settings.settings') }}</h3>

      <site-settings
          keys="BITCOIND_USER,BITCOIND_PASSWORD,BITCOIND_HOST,BITCOIND_PORT,BITCOIND_CONFIRMATIONS_REQUIRED,BITCOIND_IS_TESTNET"></site-settings>
    </main>

    <main class="mt-5 whitebox" id="bitcoin-node-page">
      <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.bitcoind-status') }}</h3>

      <!-- Submit Button -->
      <div class="form-group text-center">
        <button class="btn btn-big btn-warning" v-on:click="getBitcoinNodeStatus">{{
          $t('admin.site_settings.bitcoind-status-button') }}
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
      async getBitcoinNodeStatus() {
        try {
          let response = await axios.get('/bitcoin-node-status');
          if(response.data.status == true) {
            if(parseFloat(response.data.progress) >= 99.99) {
              this.toastSuccess(this.$t('admin.site_settings.bitcoind-status-ok'));
            } else {
              this.toastInfo(this.$t('admin.site_settings.bitcoind-status-progress') + ' ' + response.data.progress + '%');
            }
          } else {
            this.toastWarning(this.$t('admin.site_settings.bitcoind-status-error'));
          }
        } catch (e) {
          this.toastError('');
        }
      },
    }
  }
</script>
