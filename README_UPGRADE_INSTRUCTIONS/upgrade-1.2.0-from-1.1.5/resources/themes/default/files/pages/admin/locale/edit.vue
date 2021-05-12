<template>
  <div>

    <h3>{{ $t('admin.locale.edit_locale') }}</h3>

    <b-card no-body v-if="locale">
      <b-tabs card @input="refreshCodeMirrorTab">
        <b-tab active :title="$t('admin.locale.general_settings')">

          <div class="form-group">
            <label>Locale Abbreviation: </label>
            <input v-model="locale.locale" class="form-control input" type="text">
          </div>

          <div class="form-group">
            <label>Locale Name: </label>
            <input v-model="locale.name" class="form-control input" type="text">
          </div>

          <div class="form-group">
            <label>{{ $t('admin.locale.is_active') }}</label>
            <input class="form-control input-lg" type="checkbox"
                   v-model="locale.is_active">
          </div>

        </b-tab>

        <!--SECOND TAB: ID PHOTO-->
        <b-tab :title="$t('admin.locale.static_pages')">

          <div class="form-group">
            <label>Api Docs: </label>
            <codemirror v-model="locale.apidocs" :options="markdown_options" ref="cm_apidocs"></codemirror>
          </div>

          <div class="form-group">
            <label>Terms: </label>
            <codemirror v-model="locale.terms" :options="markdown_options" ref="cm_terms"></codemirror>
          </div>

        </b-tab>

        <b-tab :title="$t('admin.locale.translations')">
          <b-list-group>
            <b-list-group-item variant="light"
                               v-for="file in locale.files"
                               :key="file.name"
                               v-on:click="selectFile(file)"
                               :active="file.name === selectedFile.name" class="pointer">
              <icon name="caret-right icon-absolute"/>
              {{file.name}}
            </b-list-group-item>
          </b-list-group>

        </b-tab>
      </b-tabs>
    </b-card>

    <div class="alert-danger p-2 m-2" v-if="error">
      {{ error }}
    </div>

    <hr/>
    <div class="mt-3 text-center">
      <router-link class="btn btn-orange m-2" tag="button"
                   active-class=""
                   :to="{ name: 'admin.locale'}">
        {{$t('admin.back')}}
      </router-link>
      <button class="btn btn-green m-2"
              v-on:click="save()" :v-button-loading="busy">
        {{$t('admin.save')}}
      </button>
    </div>

    <b-modal v-if="selectedFile.name"
             v-model="showPopup"
             hide-footer
             :size="'lg'"
             :title="$t('admin.edit') + ': ' + selectedFile.name"
             @shown="refreshCodeMirrorPopup">

      <codemirror v-if="selectedFile.name"
                  v-model="selectedFile.contents"
                  :options="json_options"
                  ref="cm_popup"></codemirror>

      <button class="btn btn-orange m-2"
              v-on:click="closePopup()">
        {{$t('admin.close')}}
      </button>

    </b-modal>
  </div>
</template>

<script>
  import axios from 'axios'
  import {codemirror} from 'vue-codemirror'
  import 'codemirror/theme/base16-dark.css'
  import 'codemirror/mode/javascript/javascript.js'
  import 'codemirror/mode/markdown/markdown.js'

  export default {

    components: {
      codemirror
    },

    metaInfo() {
      return {title: 'Admin / ' + this.$t('admin.locale.title')}
    },

    data: function () {
      return {
        showPopup: false,
        selectedFile: {
          fullname: '',
          name: '',
          contents: '',
          ext: ''
        },
        locale: null,
        busy: false,
        error: null,
        markdown_options: {
          lineWrapping: true,
          tabSize: 4,
          mode: 'markdown',
          theme: 'base16-dark',
          lineNumbers: true,
          line: true,
          viewportMargin: Infinity
        },
        json_options: {
          lineWrapping: true,
          tabSize: 4,
          mode: 'javascript',
          theme: 'base16-dark',
          lineNumbers: true,
          line: true,
          viewportMargin: Infinity
        }
      }
    },

    created: function () {
      this.loadData();
    },

    methods: {

      async loadData() {
        try {
          let url = 'admin/locales/' + this.$route.params.locale;
          let response = await axios.get(url);
          this.locale = response.data.data;
        } catch (e) {
          this.toastError("Unable to fetch locale");
        }
      },

      async save() {
        if (!this.validate()) {
          return;
        }
        try {
          await axios.patch('admin/locales', this.locale);
          this.toastSuccess(this.$t('admin.locale.locale_updated'));
          this.error = null;
        } catch (e) {
          this.error = this.$t('admin.locale.' + e.response.data.message);
        }
      },

      selectFile(file) {
        this.selectedFile = file;
        this.showPopup = true;
      },

      closePopup() {
        this.showPopup = false;
        this.selectedFile = {
          fullname: '',
          name: '',
          contents: '',
          ext: ''
        };
      },

      refreshCodeMirrorPopup() {
        this.$refs.cm_popup.codemirror.refresh();
      },

      refreshCodeMirrorTab() {
        let self = this;
        setTimeout(function () {
          self.$refs.cm_terms.codemirror.refresh();
          self.$refs.cm_apidocs.codemirror.refresh();
        }, 200);
      },

      validate() {
        let valid = true;
        for (let x in this.locale.files) {
          try {
            let json = JSON.parse(this.locale.files[x].contents);
          } catch (e) {
            this.toastError("Syntax error in file: " + this.locale.files[x].name);
            valid = false;
          }
        }

        return valid;
      }
    }
  }
</script>
