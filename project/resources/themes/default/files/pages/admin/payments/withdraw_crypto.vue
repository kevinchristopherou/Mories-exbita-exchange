<template>
  <div>
    <div class="panel">
      <div id="toolbar">
        <h3>{{ $t('withdraw.withdrawals') }}</h3>
      </div>

      <div class="row">
        <div class="col-xs-12 col-md-6">
          <laravel-pagination :dataset="withdrawals" @change="loadData"></laravel-pagination>
        </div>
        <div class="col-xs-12 col-md-6 table-filter">
          <table-search :search-fields="searchFields" v-on:search="loadData"></table-search>
        </div>
      </div>

      <table class="table table-sm table-bordered table-hover table-striped">
        <thead>
        <tr>
          <th>{{ $t('user_email') }}</th>
          <th>{{ $t('withdraw.amount') }}</th>
          <th>{{ $t('withdraw.fee') }}</th>
          <th>{{ $t('withdraw.address') }}</th>
          <th>{{ $t('date') }}</th>
          <th>{{ $t('status') }}</th>
          <th>{{ $t('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="withdraw in withdrawals.data">
          <td>{{ withdraw.user.email }}</td>
          <td>{{ withdraw.amount }} {{ withdraw.currency }}</td>
          <td>{{ withdraw.fee }} {{ withdraw.currency }}</td>
          <td>{{ withdraw.address }}</td>
          <td>{{ withdraw.created_at.date | moment }}</td>
          <td>{{ $t(withdraw.status) }}</td>
          <td>
            <button v-if="withdraw.status == 'waiting'" @click="confirmModal(withdraw, true)" class="btn-sm btn">{{
              $t('approve')}}
            </button>
            <button v-if="withdraw.status == 'waiting'" @click="confirmModal(withdraw, false)"
                    class="btn-sm btn btn-danger">{{ $t('reject')}}
            </button>

            <span v-if="withdraw.status == 'approved'" class="badge badge-success" :title="getEvaluator(withdraw)">{{ $t('approved')}}</span>
            <span v-if="withdraw.status == 'rejected'" class="badge badge-danger" :title="getEvaluator(withdraw)">{{ $t('rejected')}}</span>

            <icon v-if="withdraw.status == 'rejected'" name="info-circle" class="text-muted"
                  :title="getNote(withdraw)"></icon>
          </td>
        </tr>
        </tbody>
      </table>

      <div class="row">
        <div class="col-xs-12 col-md-6">
          <laravel-pagination :dataset="withdrawals" @change="loadData"></laravel-pagination>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import moment from 'moment-timezone'
  import swal from 'sweetalert2'

  export default {
    metaInfo() {
      return {title: 'Admin / ' + this.$t('withdraw.withdrawals')}
    },

    data: function () {
      return {
        withdrawals: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 50,
            current_page: 1
          }
        },
        offset: 1,
        searchFields: [
          {name: 'user_email', field: 'user:email', type: 'text'},
          {name: 'withdraw.address', field: 'address', type: 'text'},
          {
            name: 'withdraw.status',
            field: 'status',
            type: 'select',
            options: [
              {'value': 'waiting', 'text': this.$t('waiting')},
              {'value': 'queued', 'text': this.$t('queued')},
              {'value': 'complete', 'text': this.$t('completed')},
            ]
          }
        ],
        searchParams: ''
      }
    },

    mounted() {
      this.loadData();
      this.setCurrencyFilters();
    },

    methods: {
      async setCurrencyFilters() {
        let currencyField = {
          name: 'withdraw.currency',
          field: 'currency_id',
          type: 'select',
        };

        let response = await axios.get('/currencies');

        let currencyOptions = _.map(response.data.data, function (currency) {
          return {
            value: currency.id,
            text: currency.currency
          }
        });

        currencyField.options = currencyOptions;
        this.searchFields.push(currencyField);
      },

      async loadData({params: params = this.searchParams, page: page = 1} = {}) {
        this.searchParams = params;
        try {
          let url = '/admin/withdraw/crypto?perpage=' + this.withdrawals.meta.per_page + '&page=' + page + '&' + params;
          let response = await axios.get(url);
          this.withdrawals = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async approve(withdraw) {
        try {
          await axios.patch('/admin/withdraw/crypto/approved?id=' + withdraw.id);
          withdraw.status = 'approved';
          withdraw.evaluator = this.user;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async reject(withdraw, note) {
        try {
          await axios.patch('/admin/withdraw/crypto/rejected?id=' + withdraw.id + "&note=" + note);
          withdraw.note = note;
          withdraw.status = 'rejected';
          withdraw.evaluator = this.user;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      getEvaluator(withdraw) {
        if (withdraw.evaluator && withdraw.evaluated_at) {
          return withdraw.evaluator.name + ' ' + this.$options.filters.moment(withdraw.evaluated_at.date);
        }

        return false;
      },
      getNote(withdraw) {

        if (withdraw.note) {
          return withdraw.note;
        }

        return false;
      },
      confirmModal(withdraw, state) {

        var self = this;

        swal({
          title: self.$t('confirm_action'),
          input: !state ? 'textarea' : null,
          inputPlaceholder: self.$t('comment_field_title'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: self.$t('confirm_action_yes'),
          cancelButtonText: self.$t('confirm_action_cancel'),
          preConfirm: function (textarea) {
            return new Promise(function (resolve, reject) {

              swal.enableButtons();

              if (!state && !textarea.trim()) {
                swal.showValidationError(self.$t('comment_field_required'));
              } else {
                resolve();
              }
            })
          },
          allowOutsideClick: false,
        }).then((note) => {

          state ? this.approve(withdraw) : this.reject(withdraw, note)

        }).catch(swal.noop);

      },
    },
    filters: {
      moment: function (date) {
        return moment(date).format('DD.MM.YYYY');
      }
    }
  }
</script>