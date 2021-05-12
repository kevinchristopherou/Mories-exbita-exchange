<template>
  <div>
    <h3>{{ $t('referral.title') }}</h3>

    <div class="row">
      <div class="col-xs-12 col-md-6">
        <laravel-pagination :dataset="earnings" @change="loadData"></laravel-pagination>
      </div>
    </div>

    <table class="table table-sm table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>{{ $t('referral.amount') }}</th>
        <th>{{ $t('referral.user') }}</th>
        <th>{{ $t('referral.referrer') }}</th>
        <th>{{ $t('referral.date') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="earning in earnings.data">
        <td>{{ earning.amount }} {{ earning.currency }}</td>
        <td>{{ earning.user ? earning.user.email : 'Deleted/Blocked' }}</td>
        <td>{{ earning.referrer ? earning.referrer.email : 'Deleted/Blocked' }}</td>
        <td>{{ earning.date | moment}}</td>
      </tr>
      </tbody>
    </table>
    <laravel-pagination :dataset="earnings" @change="loadData"></laravel-pagination>
  </div>
</template>

<script>
import axios from 'axios'
import moment from 'moment-timezone'
import swal from 'sweetalert2'

export default {

  metaInfo() {
    return {title: 'Admin / ' + this.$t('referral.title')}
  },

  data: function () {
    return {
      earnings: {
        data: [],
        meta: {
          last_page: 1,
          total: 0,
          per_page: 100,
          current_page: 1
        }
      },
      searchFields: [
        {name: 'admin.users.email', field: 'currency', type: 'text'},
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
        let url = 'admin/referralearnings?perpage=' + this.earnings.meta.per_page + '&page=' + page + '&' + params;
        let response = await axios.get(url);
        this.earnings = response.data;
      } catch (e) {
        this.error = e.response.data.message;
      }
    },
  },
  filters: {
    moment: function (date) {
      return moment(date).format('DD.MM.YYYY HH:mm:ss');
    }
  }
}
</script>
