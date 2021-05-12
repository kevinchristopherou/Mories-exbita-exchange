<template>
  <div>
    <div class="clearfix mt-2">
      <span> Edit File: {{file.path}}</span>
      <button class="btn btn-success float-right ml-2" v-on:click="saveFile(true)">Save & Close</button>
      <button class="btn btn-primary float-right ml-2" v-on:click="saveFile(false)">Save</button>
      <button class="btn btn-danger float-right" v-on:click="close()">Cancel</button>
    </div>
    <div class="clearfix mt-2">
      <codemirror v-model="code" :options="cmOptions" ref="myCm"></codemirror>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import {codemirror} from 'vue-codemirror'
  import 'codemirror/theme/base16-dark.css'
  import 'codemirror/mode/vue/vue.js'
  import 'codemirror/mode/css/css.js'

  export default {

    components: {
      codemirror
    },

    props: {
      file: {
        type: Object,
        default: null
      },
      mode: {
        type: String,
        default: 'css'
      }
    },

    data: function () {
      return {
        code: '',
        cmOptions: {
          tabSize: 4,
          mode: this.mode,
          theme: 'base16-dark',
          lineNumbers: true,
          line: true,
          viewportMargin: Infinity
        }
      }
    },

    mounted() {
      this.loadFile();
    },

    watch: {
      file: function () {
        this.loadFile();
        this.$refs.myCm.codemirror.setOption('mode', this.mode);
      }
    },
    methods: {
      async loadFile() {
        if (!this.file)
          return;
        let response = await axios.get('admin/theme-editor/' + this.file.id);
        this.code = response.data;
      },
      async saveFile(close) {

        try {
          let response = await axios.patch('admin/theme-editor/' + this.file.id, {code: this.code});
        }
        catch (e) {
          this.toastError('File save failed...');
        }
        this.toastSuccess('File saved...');
        if (close) {
          this.close();
        }
      },
      close() {
        this.$emit('closeFile');
      }
    }
  }
</script>