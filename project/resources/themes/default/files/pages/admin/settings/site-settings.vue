<template>
  <div>
    <div class="form-group" v-for="setting in settings">
      <label>{{ setting.label }}:
        <i class="fa fa-question-circle"
           v-b-tooltip.hover.html
           :title="setting.description"></i>
      </label>

      <a v-if="(setting.type === 'image' || setting.type === 'file') && setting.value && !setting.value.startsWith('tmp')"
         :href="setting.file_url" target="_blank" class="float-right">
        Show Existing File
      </a>
      <a v-if="(setting.type === 'image' || setting.type === 'file') && setting.value"
         class="float-right mr-2" v-on:click.prevent="triggerClearFile(setting)" href="#">
        Delete Existing File
      </a>

      <input class="form-control input-lg" :type="setting.type"
             v-if="setting.type === 'text' || setting.type === 'number'"
             v-model="setting.value" :placeholder="setting.description">

      <input class="" :type="setting.type"
             v-if="setting.type === 'checkbox'"
             v-model="setting.value" :placeholder="setting.description" :value="1">

      <file-upload v-if="setting.type === 'image'"
                   :image-preview="true"
                   :max-size="4*1024*1024"
                   accept="image/*"
                   resolution="160x50"
                   v-on:uploadComplete="setFile(setting,...arguments)"
                   v-on:clearFile="clearFile(setting, ...arguments)"
                   :ref="'setting-input-' + setting.name">
      </file-upload>

      <file-upload v-if="setting.type === 'file'"
                   :image-preview="false"
                   :max-size="4*1024*1024"
                   resolution="64x64"
                   accept=""
                   v-on:uploadComplete="setFile(setting,...arguments)"
                   v-on:clearFile="clearFile(setting, ...arguments)"
                   :ref="'setting-input-' + setting.name">
      </file-upload>
    </div>

    <!-- Submit Button -->
    <div class="form-group text-center">
      <button class="btn btn-big btn-success" v-button-loading="busy" v-on:click="saveSettings">{{
        $t('admin.save') }}
      </button>
    </div>

  </div>
</template>

<script>
  import axios from 'axios'

  export default {

    props: {
      keys: String,
    },
    data: () => ({
      busy: false,
      success: false,
      settings: []
    }),

    mounted: function () {
      this.getSettings();
    },

    methods: {
      async getSettings() {
        try {
          let response = await axios.get('/site-settings?keys=' + this.keys);
          this.settings = response.data;
        } catch (e) {
          this.error = e.response.data.message;
        }
      },
      async saveSettings() {
        try {
          let response = await axios.patch('/site-settings/', {settings: this.settings});
          this.toastSuccess('');
          this.getSettings();
          for (let x in response.data.warnings) {
            this.toastWarning(response.data.warnings[x]);
          }
        } catch (e) {
          this.toastError('');
        }
      },
      setFile(setting, file) {
        setting.value = file.tmpName;
      },
      triggerClearFile(setting) {
        let ref = 'setting-input-' + setting.name;
        this.$refs[ref][0].clear();
      },
      clearFile(setting) {
        if (!setting.value.startsWith('tmp')) {
          this.toastSuccess("File will be removed after saving the values");
        }

        setting.value = '';
        this.settings.forEach((item,key) => (item.type == "file" || item.type == "image" ? this.settings[key].value = '' : '') )

      }
    }
  }
</script>
