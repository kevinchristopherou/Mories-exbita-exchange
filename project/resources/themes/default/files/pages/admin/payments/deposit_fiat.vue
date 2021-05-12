<template>
  <div>
    <div class="panel">
      <div id="toolbar">
        <h3>{{ $t('deposit_deposits') }}</h3>
      </div>

      <div class="row">
        <div class="col-xs-12 col-md-6">
          <laravel-pagination :dataset="deposits" @change="loadData"></laravel-pagination>
        </div>
        <div class="col-xs-12 col-md-6 table-filter">
          <table-search :search-fields="searchFields" v-on:search="loadData"></table-search>
        </div>
      </div>

      <table class="table table-sm table-bordered table-hover table-striped">
        <thead>
        <tr>
          <th>{{ $t('id') }}</th>
          <th>{{ $t('deposit_amount') }}</th>
          <th>{{ $t('deposit_date') }}</th>
          <th>{{ $t('deposit_description_code') }}</th>
          <th>{{ $t('deposit_notes') }}</th>
          <th>{{ $t('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="deposit in deposits.data">
          <td>{{ deposit.id }}</td>
          <td>{{ deposit.amount }} {{ deposit.currency }}</td>
          <td>{{ deposit.created_at | tz_datetime }}</td>
          <td>{{ deposit.description }}</td>
          <td>{{ getNote(deposit) }}</td>
          <td>
            <button v-if="deposit.status == 'waiting'" @click="confirmApprove(deposit)" class="btn-sm btn">{{
              $t('deposit_approve')}}
            </button>
            <button v-if="deposit.status == 'waiting'" @click="confirmReject(deposit)"
                    class="btn-sm btn btn-danger">{{ $t('deposit_reject')}}
            </button>
            <span v-if="deposit.status == 'approved'" class="badge badge-success" :title="getEvaluator(deposit)">{{ $t('deposit_approved')}} by {{deposit.evaluator.email}} </span>
            <span v-if="deposit.status == 'rejected'" class="badge badge-danger" :title="getEvaluator(deposit)">{{ $t('deposit_rejected')}} by {{deposit.evaluator.email}}</span>
          </td>
        </tr>
        </tbody>
      </table>


      <laravel-pagination :dataset="deposits" @change="loadData"></laravel-pagination>

    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import moment from 'moment-timezone'
  import swal from 'sweetalert2'

  export default {
    metaInfo() {
      return {title: 'Admin / Fiat Deposits'}
    },

    data: function () {
      return {
        deposits: {
          data: {},
          meta: {
            total: 0,
            per_page: 30,
            from: 1,
            to: 0,
            current_page: 1
          }
        },
        offset: 1,
        searchFields: [
          {name: 'id', field: 'id', type: 'number'},
          {name: 'deposit_amount', field: 'amount', type: 'number'},
          {name: 'deposit_description_code', field: 'description', type: 'text'},
          {name: 'deposit_reject_note', field: 'note', type: 'text'},
          {name: 'deposit_bank_refcode', field: 'code', type: 'text'},
          {name: 'deposit_date', field: 'created_at', type: 'date'},
          {
            name: 'deposit_currency',
            field: 'fiat_currency_id',
            type: 'select',
            options: [
              {'value': 1, 'text': 'TRY'},
              {'value': 2, 'text': 'USD'},
            ]
          },
          {
            name: 'deposit_status',
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

    mounted: function () {
      this.loadData();
    },


    methods: {
      async search({params}) {
        try {
          let response = await axios.get('/admin/deposit/fiat?page=1&' + params);
          this.deposits = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async loadData({params: params = this.searchParams, page: page = 1} = {}) {
        this.searchParams = params;
        try {
          let url = '/admin/deposit/fiat?perpage=' + this.deposits.meta.per_page + '&page=' + page + '&' + params;
          let response = await axios.get(url);
          this.deposits = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },

      async approve(deposit, code) {
        try {
          await axios.patch('/admin/deposit/fiat/approved?id=' + deposit.id + "&code=" + code);
          deposit.status = 'approved';
          deposit.evaluator = this.user;
          deposit.code = code;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async reject(deposit, note) {
        try {
          await axios.patch('/admin/deposit/fiat/rejected?id=' + deposit.id + "&note=" + note);
          deposit.note = note;
          deposit.status = 'rejected';
          deposit.evaluator = this.user;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      getEvaluator(deposit) {
        if (deposit.evaluator) {
          return deposit.evaluator.name + ' ' + this.$options.filters.moment(deposit.evaluated_at.date);
        }

        return false;
      },
      //confirm approve modal
      confirmApprove(deposit) {
        var self = this;
        swal({
          title: self.$t('admin.deposit.confirm_approve_action'),
          input: 'text',
          inputPlaceholder: self.$t('admin.deposit.approve_bank_refcode_placeholder'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: self.$t('confirm_action_yes'),
          cancelButtonText: self.$t('confirm_action_cancel'),
          preConfirm: function (text) {
            return new Promise(function (resolve, reject) {

              swal.enableButtons();

              if (!text.trim()) {
                swal.showValidationError(self.$t('admin.deposit.approve_bank_refcode_required'));
              } else {
                resolve();
              }
            })
          },
          allowOutsideClick: false,
        }).then((note) => {
          this.approve(deposit, note)
        }).catch(swal.noop);
      },
      //confirm reject modal
      confirmReject(deposit) {
        var self = this;
        swal({
          title: self.$t('admin.deposit.confirm_reject_action'),
          input: 'textarea',
          inputPlaceholder: self.$t('admin.deposit.reject_note_placeholder'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: self.$t('confirm_action_yes'),
          cancelButtonText: self.$t('confirm_action_cancel'),
          preConfirm: function (textarea) {
            return new Promise(function (resolve, reject) {
              swal.enableButtons();
              if (!textarea.trim()) {
                swal.showValidationError(self.$t('admin.deposit.reject_note_required'));
              } else {
                resolve();
              }
            })
          },
          allowOutsideClick: false,
        }).then((note) => {
          this.reject(deposit, note)
        }).catch(swal.noop);
      },
      getNote(deposit) {

        if (deposit.status === 'approved' && deposit.code) {
          return this.$t('deposit_bank_refcode') + ':' + deposit.code;
        } else if (deposit.status === 'rejected' && deposit.note) {
          return this.$t('deposit_reject_note') + ':' + deposit.note;
        } else {
          return "-";
        }
      },
    },
    //@todo: use global filter instead
    filters: {
      moment: function (date) {
        return moment(date).format('DD.MM.YYYY');
      },
      trim: function (string) {
        return string.trim()
      }
    }
  }
</script>