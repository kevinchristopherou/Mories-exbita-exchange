<template>
  <div class="dashboard-container">
    <div class="row">
      <div class="col col-sm-12 col-md-6 mb-3">
        <crypto-wallets></crypto-wallets>
      </div>
      <div class="col col-sm-12 col-md-6 mb-3">
        <crypto-withdraws-pending></crypto-withdraws-pending>
      </div>
      <div class="col col-sm-12 col-md-6 mb-3">
        <fiat-withdraws-pending></fiat-withdraws-pending>
      </div>
      <div class="col col-sm-12 col-md-6 mb-3">
        <fiat-deposits-pending></fiat-deposits-pending>
      </div>
    </div>
  </div>
</template>

<script>
  import CryptoWallets from './dashboard/crypto-wallets';
  import CryptoWithdrawsPending from './dashboard/crypto-withdraws-pending';
  import FiatWithdrawsPending from './dashboard/fiat-withdraws-pending';
  import FiatDepositsPending from './dashboard/fiat-deposits-pending';

  export default {
    metaInfo() {
      return {title: 'Admin'}
    },

    components: {
      CryptoWallets,
      CryptoWithdrawsPending,
      FiatWithdrawsPending,
      FiatDepositsPending
    },
  }
</script>