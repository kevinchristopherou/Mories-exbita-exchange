<template src="./personal_access_token.htm"></template>

<script>
  import axios from 'axios'
  import swal from 'sweetalert2'
  import _ from 'lodash'

  export default {
    data() {
      return {
        accessToken: null,
        busy: true,
        tokens: [],
        scopes: [],

        form: {
          name: '',
          scopes: [],
        }
      };
    },
    mounted() {
      this.prepareComponent();
    },

    methods: {
      prepareComponent() {
        this.getTokens();
        this.getScopes();
      },
      setName(name) {
        this.form.name = name;
      },
      async getTokens() {
        try {

          let response = await axios.get('user/personaltoken');
          this.tokens = response.data;

        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async getScopes() {
        try {
          let response = await axios.get('user/scopes');
          this.scopes = response.data;
          this.busy = false;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      showCreateTokenForm() {
        let self = this;
        swal({
          title: self.$t('token.create_token'),
          html: self.buildScopesHtml(),
          showCancelButton: true,
          confirmButtonText: self.$t('confirm_action_create'),
          cancelButtonText: self.$t('confirm_action_cancel'),
          closeOnConfirm: true,
          preConfirm: function (input) {
            return new Promise(function (resolve, reject) {

              swal.enableButtons();

              const token = $("#personal_access_token_form input[name=token_name]").val();
              const scopes = $("#personal_access_token_form input[name=token_scopes]:checked");

              self.form.scopes = [];

              if (scopes.length > 0) {
                for (var i = 0; i < scopes.length; ++i) {
                  self.form.scopes.push(scopes[i].value);
                }
              }

              if (!token.trim()) {
                swal.showValidationError(self.$t('token.name_required'));
              } else {
                resolve([token]);
              }
            })
          },
          allowOutsideClick: false,
        }).then((result) => {
          this.setName(result[0]);
          this.store();
        }).catch(swal.noop);
      },
      store() {

        this.accessToken = null;

        try {
          axios.post('user/personaltoken', this.form)
            .then(response => {
              this.form.name = '';
              this.form.scopes = [];
              this.tokens.push(response.data.token);
              this.accessToken = response.data.accessToken;
              this.$refs.tokenSuccessModal.show();
            });
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      toggleScope(scope) {
        if (this.scopeIsAssigned(scope)) {
          this.form.scopes = _.reject(this.form.scopes, s => s == scope);
        } else {
          this.form.scopes.push(scope);
        }
      },
      scopeIsAssigned(scope) {
        return _.indexOf(this.form.scopes, scope) >= 0;
      },

      revokeConfirm(token) {
        swal({
          title: this.$t('confirm_action'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('confirm_action_yes'),
          cancelButtonText: this.$t('confirm_action_cancel'),
        }).then((result) => {
          this.revoke(token);
        }).catch(swal.noop);

      },
      async revoke(token) {
        try {
          await axios.delete('user/personaltoken/' + token.id);
          swal(
            this.$t('token.revoked'),
            this.$t('token.revoked_message'),
            'success'
          );
          this.getTokens();
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      buildScopesHtml() {
        return '<form id="personal_access_token_form">' + $('#personal_access_token_form_dummy').html() + '</form>';
      },
      copyToken() {
        var copyText = document.getElementById("personal_access_token_new_token");
        copyText.select();
        document.execCommand("Copy");
        this.toastSuccess(this.$t('token.copy_token_success'))
      }
    },
    filters: {
      truncateToken: function (string) {
        if (string.length > 15)
          return string.substring(0, 15) + '...';
        else
          return string;
      }
    }
  }
</script>
