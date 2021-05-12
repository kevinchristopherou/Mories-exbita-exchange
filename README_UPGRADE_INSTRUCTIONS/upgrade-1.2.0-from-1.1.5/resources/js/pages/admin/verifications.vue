<template>
  <div>

    <h3>{{ $t('admin.idv.verifications') }}</h3>

    <div class="row">
      <div class="col-xs-12 col-md-6">
        <laravel-pagination :dataset="verifications" @change="loadData"></laravel-pagination>
      </div>
      <div class="col-xs-12 col-md-6 table-filter">
        <table-search :search-fields="searchFields" v-on:search="loadData"></table-search>
      </div>
    </div>
    <table class="table table-sm table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>{{ $t('admin.idv.id') }}</th>
        <th>{{ $t('idv_ssid') }}</th>
        <th>{{ $t('idv_name') }}</th>
        <th>{{ $t('admin.idv.email') }}</th>
        <th>{{ $t('admin.idv.uploaded_date') }}</th>
        <th>{{ $t('admin.idv.action') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(verification,index) in verifications.data">
        <td>{{ verification.id }}</td>
        <td>{{ verification.ssid }}</td>
        <td>{{ verification.name }}</td>
        <td>{{ verification.user.email }}</td>
        <td>{{ verification.created_at | tz_datetime }}</td>
        <td>
          <button v-if="verification.status == 'waiting'" v-on:click="setCurrent(verification,index)"
                  class="btn-sm btn btn-danger">{{ $t('evaluate')}}
          </button>
          <span v-if="verification.status == 'approved'" class="badge badge-success"
                :title="getEvaluator(verification)">{{ $t('admin.idv.verified')}}</span>
          <span v-if="verification.status == 'rejected'" class="badge badge-danger"
                :title="getEvaluator(verification)">{{ $t('admin.idv.rejected')}}</span>

        </td>
      </tr>
      </tbody>
    </table>

    <b-modal v-model="showEvaluatePopup"
             hide-footer
             :size="'lg'"
             :title="$t('admin.idv.evaluation_title')">

      <b-card no-body v-if="currentDocument">
        <b-tabs card v-model="tabIndex">
          <b-tab active :title="$t('idv_basic_information')">

            <div class="text-left">
              <strong class="my-2">{{$t('idv_name')}}</strong>: {{currentDocument.name}}
              <br/>
              <strong class="my-2">{{$t('idv_ssid')}}</strong>: {{currentDocument.ssid}}
              <br/>
              <strong class="my-2">{{$t('idv_address')}}</strong>: {{currentDocument.address}}
            </div>
          </b-tab>

          <!--SECOND TAB: ID PHOTO-->
          <b-tab :title="$t('idv_identity_photo')">

            <div class="text-left">
              <strong class="my-2">{{$t('idv_name')}}</strong>: {{currentDocument.name}}
              <br/>
              <strong class="my-2">{{$t('idv_ssid')}}</strong>: {{currentDocument.ssid}}
            </div>

            <div>
              <img v-bind:src="currentDocument.identity_photo.signed_url"
                   class="width-full-height-auto"/>
            </div>
          </b-tab>

          <!--THIRD TAB: SELFIE PHOTO-->
          <b-tab :title="$t('idv_selfie_photo')">
            <div>
              <img v-bind:src="currentDocument.selfie_photo.signed_url"
                   class="width-full-height-auto"/>
            </div>
          </b-tab>

          <!--FOURTH TAB: ADDRESS PHOTO-->
          <b-tab :title="$t('idv_address_photo')">
            <div class="text-left">
              <strong class="my-2">{{$t('idv_address')}}</strong>: {{currentDocument.address}}
            </div>
            <div>
              <img v-bind:src="currentDocument.address_photo.signed_url"
                   class="width-full-height-auto"/>
            </div>
          </b-tab>
        </b-tabs>
      </b-card>

      <hr/>
      <div class="mt-3 text-center">
        <button class="btn btn-orange m-2"
                v-on:click="reject(currentDocument,true)" :v-button-loading="busy">
          {{$t('admin.idv.reject_and_next')}}
        </button>
        <button class="btn btn-orange m-2"
                v-on:click="reject(currentDocument,false)" :v-button-loading="busy">
          {{$t('admin.idv.reject')}}
        </button>

        <button class="btn btn-secondary m-2"
                v-on:click="moveNext()" :v-button-loading="busy">
          {{$t('admin.idv.jump_next')}}
        </button>

        <button class="btn btn-green m-2"
                v-on:click="verify(currentDocument,false)" :v-button-loading="busy">
          {{$t('admin.idv.approve')}}
        </button>
        <button class="btn btn-green m-2"
                v-on:click="verify(currentDocument,true)" :v-button-loading="busy">
          {{$t('admin.idv.approve_and_next')}}
        </button>
      </div>
    </b-modal>

    <laravel-pagination :dataset="verifications" @change="loadData"></laravel-pagination>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {

    metaInfo() {
      return {title: 'Admin / ' +  this.$t('admin.idv.verifications') }
    },

    data: function () {
      return {
        busy: false,
        showEvaluatePopup: false,
        currentDocument: null,
        currentIndex: null,
        tabIndex: 0,
        verifications: {
          data: {},
          meta: {
            last_page: 1,
            total: 0,
            per_page: 20,
            current_page: 1
          }
        },
        searchFields: [
          {
            name: 'status', field: 'status', type: 'select',
            options: [
              {'value': 'waiting', 'text': this.$t('waiting')},
              {'value': 'approved', 'text': this.$t('approved')},
              {'value': 'rejected', 'text': this.$t('rejected')},
            ]
          },
          {name: 'idv_ssid', field: 'ssid', type: 'number'},
          {name: 'idv_name', field: 'name', type: 'text'},
          {name: 'date', field: 'created_at', type: 'date'},
          {name: 'id', field: 'id', type: 'number'},
        ],
        searchParams: 'status=waiting'
      }
    },

    created: function () {
      this.loadData();
    },
    methods: {

      setCurrent(verification, index) {
        this.currentDocument = verification;
        this.currentIndex = index;
        this.showEvaluatePopup = true;
        this.tabIndex = 0;
      },

      unsetCurrent() {
        this.currentDocument = null;
        this.currentIndex = null;
        this.showEvaluatePopup = false;
      },

      async loadData({params: params = this.searchParams, page: page = 1} = {}) {

        this.searchParams = params;

        try {
          let url = 'verifications?perpage=' + this.verifications.meta.per_page + '&page=' + page + '&' + this.searchParams;
          let response = await axios.get(url);
          this.verifications = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async verify(verification, next) {

        try {
          await axios.get('verification/verify/' + verification.id);
          verification.status = 'approved';
          verification.evaluator = this.user;
          if (next) {
            this.moveNext();
          }
          else {
            this.unsetCurrent();
          }
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async reject(verification, next) {
        try {
          this.busy = true;
          await axios.get('verification/reject/' + verification.id);
          verification.status = 'rejected';
          verification.evaluator = this.user;
          if (next) {
            this.moveNext();
          } else {
            this.unsetCurrent();
          }
        } catch (e) {
          this.error = e.response.data.message;
        }

        this.busy = false;
      },
      moveNext() {
        let x = this.currentIndex + 1;
        while (x < this.verifications.data.length) {
          if (this.verifications.data[x].status === 'waiting') {
            this.setCurrent(this.verifications.data[x], x);
            return;
          }
        }

        this.unsetCurrent();
      },
      getEvaluator(validation) {
        if (validation.evaluator) {
          return validation.evaluator.name + ' ' + this.$options.filters.tz_datetime(validation.evaluated_at.date);
        }
        return false;
      }
    }
  }
</script>
