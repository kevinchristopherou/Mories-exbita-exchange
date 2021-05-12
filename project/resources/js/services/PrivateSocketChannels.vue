<template>
  <div>
  </div>
</template>


<script>
  import {mapGetters} from 'vuex'

  export default {

    name: 'private-socket-channels',

    data: () => {
      return {
        channelName: null
      }
    },

    computed: mapGetters({
      authCheck: 'authCheck',
      socketId: 'getSocketId'
    }),

    watch: {
      socketId: function (val, oldVal) {
        if (this.authCheck) {
          this.joinChannels();
        }
      },
      // authCheck: function (val, oldVal) {
      //   if (val && !oldVal && this.socketId) {
      //     this.joinChannels();
      //   } else {
      //     if (!val && oldVal && this.socketId) {
      //       console.log('leave user channel');
      //       window.Echo.leave(this.channelName);
      //       this.channelName = null;
      //     }
      //   }
      // }
    },

    methods: {
      joinChannels: function () {

        let channelName = 'private-user-' + this.user.id;
        if (channelName in window.Echo.connector.channels) {
          return;
        }

        let self = this;
        window.Echo.private('user-' + this.user.id)
        //Listen Orders
          .listen('OrderUpdated', (e) => {
            if (e.action === "created" || e.action === "updated") {
              //No need to show toaster, it is already toasted by the order form post
              self.$store.dispatch('updateOrder', {order: e.order});
            }

            else if (e.action === "cancel") {
              self.$store.dispatch('removeOrder', {order: e.order});
              this.toastInfo(this.getOrderBrief(e.order) + ' ' + this.$t('order.canceled'));
            }

            else if (e.action === "processed") {
              self.$store.dispatch('removeOrder', {order: e.order});
              this.toastInfo(this.getOrderBrief(e.order) + ' ' + this.$t('order.processed'));
            }
          })
          //Listen Wallets
          .listen('WalletUpdated', (e) => {
            self.$store.dispatch('updateWallet', {wallet: e.wallet});
          })
          //Listen Fiat wallets
          .listen('FiatWalletUpdated', (e) => {
            self.$store.dispatch('updateFiatWallet', {fiatWallet: e.fiatWallet});
          })
          //Listen Account Verification Status
          .listen('IdVerificationEvaluated', (e) => {
            if (e.status === 1) {
              this.toastSuccess(this.$t('idv_toast_approved'));
              this.$store.dispatch('fetchUser');
            }
            else {
              this.toastError(this.$t('idv_toast_rejected'));
            }
          })
          //Listen FiatDepositEvaluated
          .listen('FiatDepositEvaluated', (e) => {
            if (e.fiatDeposit.status === 'approved') {
              this.toastSuccess(this.$t('deposit_fiat.toast_approved', e.fiatDeposit));
            }
            else {
              this.toastError(this.$t('deposit_fiat.toast_rejected', e.fiatDeposit));
            }
          })
          .listen('BlockchainTxReceived', (e) => {
            e.amount = this.$options.filters.round(e.amount, 8);
            this.toastInfo(this.$t('deposit.tx_received', e));
          })
          .listen('BlockchainTxConfirmed', (e) => {
            e.amount = this.$options.filters.round(e.amount, 8);
            this.toastInfo(this.$t('deposit.tx_confirmed', e));
          });
      }
    }
  }
</script>