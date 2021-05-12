<template>
  <div class="card dashboard-card">
    <div class="card-header">
      <router-link :to="{ name: 'admin.payments.withdraw.crypto'}"class="float-left">
        {{ $t('admin.dashboard.crypto_withdraws_pending') }}</router-link>
      <button class="btn btn-sm btn-outline-warning float-right" v-on:click="loadData" v-button-loading="busy">
        <i v-if="!busy" class="mr-2 fa fa-refresh mt-1"></i>
        {{ withdraws.meta.total }}
      </button>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm table-bordered table-hover table-striped table-responsive-md mb-0">
        <thead>
        <tr>
          <th>{{ $t('admin.crypto_withdraws_pending.currency') }}</th>
          <th>{{ $t('admin.crypto_withdraws_pending.amount') }}</th>
          <th>{{ $t('status') }}</th>
          <th>{{ $t('date') }}</th>
          <th>{{ $t('admin.crypto_withdraws_pending.address') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="withdraw in withdraws.data">
          <td>{{ withdraw.currency }}</td>
          <td class="text-right">{{ withdraw.amount }}</td>
          <td class="text-right">
            {{$t(withdraw.status)}}
          </td>
          <td class="text-right">
            {{ withdraw.created_at | tz_datetime }}
          </td>
          <td class="text-right">{{ withdraw.address }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import Vue from 'vue';

  export default {

    data: function () {
      return {
        withdraws: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        busy: false,
      }
    },

    mounted: function () {
      this.loadData();
    },

    methods: {
      async loadData() {
        try {
          this.busy = true;
          let url = 'admin/withdraw/crypto?status=waiting,queued';
          let response = await axios.get(url);
          this.withdraws = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }

        this.busy = false;
      },
    }
  }
</script>