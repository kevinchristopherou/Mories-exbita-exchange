<template src="./id_verification.htm"></template>

<script>
  import axios from 'axios'

  export default {

    data: function () {
      return {
        waitingDocuments: null,
        showForm: false,
        identityHelpText: this.$t('idv_identity_helptext'),
        selfieHelpText: this.$t('idv_selfie_helptext'),
        addressHelpText: this.$t('idv_address_helptext'),
        form: {
          name: '',
          ssid: '',
          address: '',
          identity_photo: '',
          selfie_photo: '',
          address_photo: '',
        },
        errors: [],
        error: '',
        buttonDisabled: true,
        busy: false,
        loading: true,

        //Tab management
        activeTab: 1,
        stepOneCompleted: false,
        stepTwoCompleted: false,
        stepThreeCompleted: false,
        stepFourCompleted: false,
        tab4Class: 'itemclass'
      }
    },

    watch: {
      form: {
        handler(val) {

          this.buttonDisabled = false;

          for (let x in val) {
            if (!val[x]) {
              this.buttonDisabled = true;
            }
          }

          this.stepOneCompleted = (val.name && val.ssid && val.address);
        },
        deep: true
      }
    },

    mounted() {
      if (!this.user.id_verified) {
        this.fetchDocuments();
      }
    },

    methods: {
      async fetchDocuments() {
        try {
          let {data} = await axios.get('iddocuments');
          this.waitingDocuments = data.data;
          this.loading = false;
        } catch (e) {
          this.loading = false;
        }
      },
      toggleForm() {
        this.showForm = !this.showForm;
      },
      setIdentityFile(file) {
        this.form.identity_photo = file.tmpName;
        this.stepTwoCompleted = true;
      },
      clearIdentityFile() {
        this.form.identity_photo = '';
        this.stepTwoCompleted = false;
      },
      setSelfieFile(file) {
        this.form.selfie_photo = file.tmpName;
        this.stepThreeCompleted = true;
      },
      clearSelfieFile() {
        this.form.selfie_photo = '';
        this.stepThreeCompleted = false;
      },
      setAddressFile(file) {
        this.form.address_photo = file.tmpName;
        this.stepFourCompleted = true;
        //assign a random class to tab4 to force refresh it's content
        this.tab4Class = 'itemclass' + Math.random();
      },
      clearAddressFile() {
        this.form.address_photo = '';
        this.stepFourCompleted = false;
        //assign a random class to tab4 to force refresh it's content
        this.tab4Class = 'itemclass' + Math.random();
      },

      async sendDocuments() {
        try {
          this.busy = true;
          await axios.post('iddocuments', this.form);
          this.fetchDocuments();
        } catch (e) {
          this.toastError(this.$t('idv_upload_failed'));
        }

        this.busy = false;
      }
    }
  }
</script>
