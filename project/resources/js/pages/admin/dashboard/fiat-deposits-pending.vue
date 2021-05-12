<template>
  <div class="card dashboard-card">
    <div class="card-header">
      <router-link :to="{ name: 'admin.payments.deposit.fiat'}"class="float-left">
        {{ $t('admin.dashboard.fiat_deposits_pending') }}
      </router-link>
      <button class="btn btn-sm btn-outline-warning float-right" v-on:click="loadData" v-button-loading="busy">
        <i v-if="!busy" class="mr-2 fa fa-refresh mt-1"></i>
        {{ deposits.meta.total }}
      </button>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm table-bordered table-hover table-striped table-responsive-md mb-0">
        <thead>
        <tr>
          <th>{{ $t('deposit_currency') }}</th>
          <th>{{ $t('deposit_amount') }}</th>
          <th>{{ $t('deposit_date') }}</th>
          <th>{{ $t('deposit_description_code') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="deposit in deposits.data">
          <td>{{ deposit.currency }}</td>
          <td class="text-right">{{ deposit.amount }} </td>
          <td>{{ deposit.created_at | tz_datetime }}</td>
          <td>{{ deposit.description }}</td>
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
        deposits: {
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
          let url = 'admin/deposit/fiat?status=waiting';
          let response = await axios.get(url);
          this.deposits = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }

        this.busy = false;
      },
    }
  }
</script>