<template>
  <div>
    <h3>{{ $t('admin.users.users') }}</h3>

    <div class="row">
      <div class="col-xs-12 col-md-6">
        <laravel-pagination :dataset="users" @change="loadData"></laravel-pagination>
      </div>
      <div class="col-xs-12 col-md-6 table-filter">
        <table-search :search-fields="searchFields" v-on:search="loadData"></table-search>
      </div>
    </div>

    <table class="table table-sm table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>{{ $t('admin.users.id') }}</th>
        <th>{{ $t('admin.users.name') }}</th>
        <th>{{ $t('admin.users.email') }}</th>
        <th>{{ $t('admin.users.group') }}</th>
        <th>{{ $t('admin.users.idv_status') }}</th>
        <th>{{ $t('admin.users.registered') }}</th>
        <th>{{ $t('actions') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in users.data">
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.role }}</td>
        <td>
          <span v-if="user.id_verified == 1" class="badge badge-success">{{$t('admin.users.idv_verified')}}</span>
          <span v-if="user.id_verified == 0" class="badge badge-danger">{{$t('admin.users.idv_non_verified')}}</span>
        </td>
        <td>{{ user.created_at.date | moment }}</td>
        <td>
          <button @click="blockUserConfirm($event,user)" class="btn-sm btn">{{ isBlockedText(user)}}</button>
        </td>
      </tr>
      </tbody>
    </table>
    <laravel-pagination :dataset="users" @change="loadData"></laravel-pagination>
  </div>
</template>

<script>
  import axios from 'axios'
  import moment from 'moment-timezone'
  import swal from 'sweetalert2'

  export default {

    metaInfo() {
      return {title: 'Admin / ' + this.$t('admin.users.users')}
    },

    data: function () {
      return {
        users: {
          data: [],
          meta: {
            last_page: 1,
            total: 0,
            per_page: 50,
            current_page: 1
          }
        },
        searchFields: [
          {name: 'admin.users.email', field: 'email', type: 'text'},
          {
            name: 'admin.users.block_status',
            field: 'is_blocked',
            type: 'select',
            options: [
              {'value': '1', 'text': this.$t('yes')},
              {'value': '0', 'text': this.$t('no')},
            ]
          },
          {
            name: 'admin.users.idv_status',
            field: 'id_verified',
            type: 'select',
            options: [
              {'value': '1', 'text': this.$t('yes')},
              {'value': '0', 'text': this.$t('no')},
            ]
          },
          {name: 'admin.users.registered', field: 'created_at', type: 'date'},
          {name: 'admin.users.id', field: 'id', type: 'number'}
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
          let url = 'users?perpage=' + this.users.meta.per_page + '&page=' + page + '&' + params;
          let response = await axios.get(url);
          this.users = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async blockUser(user) {
        try {
          await axios.get('/user/block/' + user.id);
          user.is_blocked = !user.is_blocked;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      blockUserConfirm(event, user) {

        swal({
          title: this.$t('confirm_action'),
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: this.$t('confirm_action_yes'),
          cancelButtonText: this.$t('confirm_action_cancel'),
          focusConfirm: false,
        }).then((result) => {
          if (result) {
            this.blockUser(user)
          }
        }).catch(swal.noop);
      },
      isBlockedText(user) {
        if (user.is_blocked) {
          return this.$t('admin.users.unblock');
        }

        return this.$t('admin.users.block');
      },

    },
    filters: {
      moment: function (date) {
        return moment(date).format('DD.MM.YYYY');
      }
    }
  }
</script>