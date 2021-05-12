<template src="./deposit_crypto.htm"></template>

<script>
  import Form from 'vform'
  import axios from 'axios'
  import swal from 'sweetalert2'
  import QrcodeVue from 'qrcode.vue'

  export default {

    components: {
      QrcodeVue
    },

    data: function () {
      return {
        busy: false,
        address: null,
        form: new Form({
          currency_id: '',
        }),
        currency: null,
        selectedIndex: null,
        showNewAddress: false,
        autoGenerate: false,
        error: '',
        pending: [],
        history: {
          data: [],
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        searchFields: [
          {name: 'tx_id', field: 'txid', type: 'text'},
          {name: 'deposit_amount', field: 'amount', type: 'number'},
          {name: 'date', field: 'created_at', type: 'date'},
        ],
        searchParams: ''
      }
    },

    mounted: function () {
      if (!this.user.id_verified) {
        this.$router.push({name: 'settings.id_verification'})
      }
      this.loadData();
      this.loadPending();
    },

    computed: {
      wallets() {
        return this.$store.getters.getWallets;
      }

    },

    methods: {
      async loadPending() {
        let params = '';
        if (this.currency !== null) {
          params = 'currency_id=' + this.currency.currency_id;
        }
        try {
          let url = '/deposit/crypto/pending?' + params;
          let response = await axios.get(url);
          this.pending = response.data.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async loadData({params: params = this.searchParams, page: page = 1} = {}) {
        this.searchParams = params;
        if (this.currency !== null) {
          params += '&currency_id=' + this.currency.currency_id;
        }
        try {
          let url = '/deposit/crypto/history?perpage=' + this.history.meta.per_page + '&page=' + page + '&' + params;
          let response = await axios.get(url);
          this.history = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async generateAddress(autoGenerate) {
        try {
          this.busy = true;

          let response = await this.form.post('/deposit/crypto/generate?currency=' + this.currency.currency_id);

          if (response.data) {
            this.setAddress(response.data.data);
            //address value in the wallet will be updated via WalletUpdated event over socket.
            if (!autoGenerate) {
              this.toastSuccess('');
            }
            this.busy = false;
          }

        } catch (e) {
          this.busy = false;
          this.error = e.response.data.message;
        }

        this.busy = false;
      },
      setCurrency(currency) {
        this.currency = currency;
      },
      setAddress(address) {
        this.address = address;
        if (!this.address) {
          this.generateAddress(true);
          this.autoGenerate = true;
          this.showNewAddress = false;
        }
      },
      selectWallet(e) {
        if (e.target.options.selectedIndex == 0) {
          this.currency = null;
          this.loadData();
        } else {
          this.showNewAddress = true;
          this.autoGenerate = false;
          this.selectedIndex = e.target.options.selectedIndex - 1;
          this.setCurrency(this.wallets[this.selectedIndex]);
          this.setAddress(this.currency.crypto_address);
          this.loadData();
          this.loadPending();
        }
      },
      generateAddressConfirm() {
        this.swalConfirm(this.$t('deposit_address_generation.caution')).then((result) => {
          this.autoGenerate = false;
          this.generateAddress(false);
        }).catch(swal.noop);
      },
      copyAddress() {
        var copyText = document.getElementById("walletAddress");
        copyText.select();
        document.execCommand("Copy");
        this.toastSuccess(this.$t('deposit_copy_success'))
      }
    },
  }
</script>