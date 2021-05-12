<template>
  <div>

    <h3>{{ $t('deposit_deposits') }}</h3>

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
        <th>{{ $t('user_email') }}</th>
        <th>{{ $t('deposit_amount') }}</th>
        <th>{{ $t('deposit_currency') }}</th>
        <th>{{ $t('deposit_address') }}</th>
        <th>{{ $t('tx_id') }}</th>
        <th>{{ $t('date') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="deposit in deposits.data">
        <td>{{ deposit.user_email }}</td>
        <td :class="{'bg-warning': deposit.ignored_deposit}">{{ deposit.amount }}</td>
        <td>{{ deposit.currency }}</td>
        <td>{{ deposit.address ? deposit.address.address : 'N/A' }}</td>
        <td>{{ deposit.txid }}</td>
        <td>{{ deposit.date | tz_datetime }}</td>
      </tr>
      </tbody>
    </table>
    <laravel-pagination :dataset="deposits" @change="loadData"></laravel-pagination>
  </div>
</template>

<script>
  import axios from 'axios'
  import moment from 'moment-timezone'

  export default {
    metaInfo() {
      return {title: 'Admin' + this.$t('deposit_deposits')}
    },

    data: function () {
      return {
        deposits: {
          data: [],
          meta: {
            last_page: 1,
            total: 0,
            per_page: 50,
            current_page: 1
          }
        },
        searchFields: [
          {name: 'tx_id', field: 'txid', type: 'text'},
          {name: 'user_email', field: 'user:email', type: 'text'},
          {name: 'deposit_currency', field: 'currency_id', type: 'select', options: []},
          {name: 'date', field: 'created_at', type: 'date'},
          {name: 'id', field: 'id', type: 'number'},
        ],
        searchParams: ''
      }
    },

    mounted() {
      try {

        let response = axios.get('currencies');
        let currencies = _.map(response.data, (currency) => {
          return {
            value: currency.id,
            text: currency.symbol
          };
        });

        this.searchFields[2].options = currencies;
      } catch (e) {

      }
      this.loadData();
    },

    methods: {
      async loadData({params: params = this.searchParams, page: page = 1} = {}) {
        this.searchParams = params;
        try {
          let url = '/admin/deposit/crypto?perpage=' + this.deposits.meta.per_page + '&page=' + page + '&' + params;
          let response = await axios.get(url);
          this.deposits = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },

    },
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