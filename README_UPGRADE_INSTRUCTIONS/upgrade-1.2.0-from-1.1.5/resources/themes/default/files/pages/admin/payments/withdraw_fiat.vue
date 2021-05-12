<template>
  <div>
    <h3>{{ $t('withdraw.fiat_withdrawals') }}</h3>

    <div class="row">
      <div class="col-xs-12 col-md-6">
        <laravel-pagination :dataset="withdrawal" @change="loadData"></laravel-pagination>
      </div>
      <div class="col-xs-12 col-md-6 table-filter">
        <table-search :search-fields="searchFields" v-on:search="loadData"></table-search>
      </div>
    </div>
    <table class="table table-sm table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>{{ $t('id') }}</th>
        <th>{{ $t('withdraw.amount') }}</th>
        <th>{{ $t('withdraw.bank_name') }}</th>
        <th>{{ $t('withdraw.recipient') }}</th>
        <th>{{ $t('withdraw.iban') }}</th>
        <th>{{ $t('withdraw.swift_code') }}</th>
        <th>{{ $t('date') }}</th>
        <th>{{ $t('actions') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="withdraw in withdrawal.data">
        <td>{{ withdraw.id }}</td>
        <td>{{ withdraw.amount }} {{ withdraw.currency }}</td>
        <td>{{ withdraw.bank_name }}</td>
        <td>{{ withdraw.recipient }}</td>
        <td>{{ withdraw.iban }}</td>
        <td>{{ withdraw.swift_code }}</td>
        <td>{{ withdraw.created_at | tz_datetime}}</td>
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
    <laravel-pagination :dataset="withdrawal" @change="loadData"></laravel-pagination>

  </div>
</template>

<script>
  import axios from 'axios'
  import moment from 'moment-timezone'
  import swal from 'sweetalert2'

  export default {

    metaInfo() {
      return {title: 'Admin / Withdrawal'}
    },

    data: function () {
      return {
        withdrawal: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        searchFields: [
          {name: 'id', field: 'id', type: 'number'},
          {name: 'withdraw.amount', field: 'amount', type: 'number'},
          {name: 'withdraw.bank_name', field: 'bank_name', type: 'text'},
          {name: 'withdraw.recipient', field: 'recipient', type: 'text'},
          {name: 'withdraw.iban', field: 'iban', type: 'text'},
          {name: 'withdraw.swift_code', field: 'swift_code', type: 'text'},
          {name: 'date', field: 'created_at', type: 'date'},
          {
            name: 'withdraw.currency',
            field: 'fiat_currency_id',
            type: 'select',
            options: [
              {'value': 1, 'text': 'TRY'},
              {'value': 2, 'text': 'USD'},
            ]
          },
          {
            name: 'withdraw.status',
            field: 'status',
            type: 'select',
            options: [
              {'value': 'waiting', 'text': this.$t('waiting')},
              {'value': 'approved', 'text': this.$t('approved')},
              {'value': 'rejected', 'text': this.$t('rejected')},
            ]
          }
        ],
        searchParams: ''
      }
    },

    created: function () {
      this.loadData();
    },

    methods: {
      async loadData({params: params = this.searchParams, page: page = 1} = {}) {

        this.searchParams = params;

        try {
          let url = 'admin/withdraw/fiat?perpage=' + this.withdrawal.meta.per_page + '&page=' + page + '&' + params;
          let response = await axios.get(url);
          this.withdrawal = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async approve(withdraw) {
        try {
          await axios.patch('/admin/withdraw/fiat/approved?id=' + withdraw.id);
          withdraw.status = 'approved';
          withdraw.evaluator = this.user;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async reject(withdraw, note) {
        try {
          await axios.patch('/admin/withdraw/fiat/rejected?id=' + withdraw.id + "&note=" + note);
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