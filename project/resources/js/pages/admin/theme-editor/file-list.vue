<template>
  <div>
    <div class="row">
      <div class="col col-5 overflow-auto" v-bind:style="{height: divHeight}">
        <h4 class="mb-3">Theme Files</h4>
        <b-list-group>
          <b-list-group-item variant="dark" class="">Vue Template Files</b-list-group-item>
          <b-list-group-item variant="light" v-for="file in files.html" :key="file.id"
                             v-on:click="selectFile(file,'vue')"
                             :active="file.selected" class="pointer">
            <icon name="caret-right" class="icon-absolute"/>
            {{file.path}}
          </b-list-group-item>
          <b-list-group-item variant="dark">Stylesheet Files</b-list-group-item>
          <b-list-group-item variant="light" v-for="file in files.css" :key="file.id"
                             v-on:click="selectFile(file,'css')"
                             :active="file.selected" class="pointer">
            <icon name="caret-right"/>
            {{file.path}}
          </b-list-group-item>
        </b-list-group>

      </div>
      <div class="col col-7 overflow-auto" v-bind:style="{height: divHeight}">
        <edit-file :file="selectedFile" :mode="selectedMode" v-if="selectedFile"
                   v-on:closeFile="unselectFile()"></edit-file>
      </div>
    </div>

    <div class="row mt-2 mb-2">
      <div class="col-12">
        Please note: Preview Site will work only after the Build For Preview process finishes
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-12">
        <button class="btn btn-primary float-left" v-on:click="build('preview')">Build For Preview</button>
        <a class="btn btn-outline-info float-left ml-2" target="_blank" href="/preview/">Preview Site</a>
        <button class="btn btn-danger float-right" v-on:click="build('production')">Build For Production</button>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from 'axios'
  import EditFile from "./edit-file";

  export default {

    components: {EditFile},

    metaInfo() {
      return {title: 'Admin / Theme Editor / File List'}
    },

    data: function () {
      return {
        files: {
          html: [],
          css: []
        },
        selectedFile: null,
        seledtedMode: null,
        divHeight: 100
      }
    },

    mounted() {
      this.loadData();
      this.handleResize();
      window.addEventListener('resize', this.handleResize);
    },
    beforeDestroy: function () {
      window.removeEventListener('resize', this.handleResize)
    },

    methods: {
      async loadData() {
        let response = await axios.get('admin/theme-editor');
        this.files = response.data;
      },

      async build(type) {
        this.swalConfirm('This will compile the css/vue files using webpack and deploy the changes to ' + type + ' site. <br><br>Any syntax error wrong vue code changes may break the site.<br><br>Do you really want to continue? ').then(async (result) => {
          try {
            await axios.get('admin/theme-editor/trigger-build/' + type);
            this.toastSuccess("Frontend build has been triggered. You will be notified when it is completed");
          } catch (e) {
            this.toastError("Unable to trigger frontend build. Please contact support.");
          }
        }).catch(swal.noop);


      },

      selectFile(file, mode) {
        this.unselectFile();
        this.selectedFile = file;

        file.selected = true;
        this.selectedFile = file;
        this.selectedMode = mode;

      },

      unselectFile() {
        if (this.selectedFile !== null) {
          this.selectedFile.selected = false;
        }
        this.selectedFile = null;
      },
      handleResize(event) {
        this.divHeight = (window.innerHeight - 80) + 'px';
      }
    },

  }
</script>
