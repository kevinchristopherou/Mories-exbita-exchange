<template>
  <div>

    <h3>{{ $t('admin.locale.title') }}</h3>

    <div class="row">
      <div class="col-xs-12 col-md-6">
        <button class="btn btn-sm btn-primary mt-2" v-on:click="showPopup = true">{{$t('admin.add_new')}}</button>
      </div>
      <div class="col-xs-12 col-md-6 table-filter">
        <table-search :search-fields="searchFields" v-on:search="loadData"></table-search>
      </div>
    </div>
    <table class="table table-sm table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>{{ $t('admin.locale.locale') }}</th>
        <th>{{ $t('admin.locale.name') }}</th>
        <th>{{ $t('admin.locale.is_active') }}</th>
        <th>{{ $t('actions') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(locale,index) in locales.data">
        <td>{{ locale.locale }}</td>
        <td>{{ locale.name }}</td>
        <td>{{ locale.is_active === 1 ? $t('yes') : $t('no') }}</td>
        <td>
          <router-link tag="button" :to="{name: 'admin.locale.edit', params: {locale: locale.locale}}"
                       class="btn-sm btn btn-primary">
            {{ $t('admin.edit')}}
          </router-link>
          <button v-on:click="deletelocale(locale)"
                  class="btn-sm btn btn-danger">
            {{ $t('admin.delete')}}
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <laravel-pagination :dataset="locales" @change="loadData"></laravel-pagination>

    <b-modal v-model="showPopup"
             hide-footer
             :size="'lg'"
             :title="$t('admin.locale.edit_locale')">

      <div class="form-group">
        <label>Locale Abbreviation: </label>
        <input v-model="currentItem.locale" class="form-control input" type="text">
      </div>

      <div class="form-group">
        <label>Locale Name: </label>
        <input v-model="currentItem.name" class="form-control input" type="text">
      </div>


      <div class="alert-danger p-2" v-if="error">
        {{ error }}
      </div>
      <hr/>
      <div class="mt-3 text-center">
        <button class="btn btn-orange m-2"
                v-on:click="unsetCurrent()">
          {{$t('admin.close')}}
        </button>
        <button class="btn btn-green m-2"
                v-on:click="save()" :v-button-loading="busy">
          {{$t('admin.save')}}
        </button>
      </div>

    </b-modal>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {

    metaInfo() {
      return {title: 'Admin / ' + this.$t('admin.locale.title')}
    },

    data: function () {
      return {
        busy: false,
        showPopup: false,
        currentItem: {},
        currencies: [],
        fiatCurrencies: [],
        error: null,
        locales: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 10,
            current_page: 1
          }
        },
        searchFields: [
          {name: 'name', field: 'name', type: 'text'},
        ],
        searchParams: ''
      }
    },

    created: function () {
      this.unsetCurrent();
      this.loadData();
    },

    methods: {

      async setCurrent(locale) {
        try {
          let response = await axios.get('/admin/locales/' + locale.id);
          this.currentItem = response.data.data;
          this.showPopup = true;
        } catch (e) {
          this.toastError("Unable to fetch locale");
        }
      },

      unsetCurrent() {
        this.currentItem = {
          id: 0,
          name: '',
          locale: ''
        };
        this.showPopup = false;
      },

      async loadData({params: params = this.searchParams, page: page = 1} = {}) {

        this.searchParams = params;

        try {
          let url = 'admin/locales?perpage=' + this.locales.meta.per_page + '&page=' + page + '&' + this.searchParams;
          let response = await axios.get(url);
          this.locales = response.data;
        } catch (e) {
          this.toastError("Unable to fetch locales");
        }
      },
      async save() {
        try {
          await axios.post('admin/locales', this.currentItem);
          this.toastSuccess('locale_saved');
          this.showPopup = false;
          this.error = null;
          this.loadData();

        } catch (e) {
          this.error = this.$t('admin.exchange_settings.locales.' + e.response.data.message);
        }
      },

      async deletelocale(locale) {
        if (!confirm(this.$t('confirm_action'))) {
          return;
        }
        try {
          this.busy = true;
          await axios.delete('admin/locales/' + locale.id);
          this.loadData();
        } catch (e) {
          this.error = e.response.data.message;
        }

        this.busy = false;
      }
    }
  }
</script>
