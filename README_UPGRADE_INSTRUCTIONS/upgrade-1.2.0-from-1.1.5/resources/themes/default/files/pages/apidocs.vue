<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 class="title-h2 color-dark-blue text-center text-uppercase">{{$t('api_documentation')}}</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="text-center" v-if="html === ''">
          <icon class="spinner" spin scale="4x"></icon>
        </div>
        <div v-html="html">

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import showdown from "showdown"
  import axios from "axios"

  export default {
    layout: 'main',
    metaInfo() {
      return {title: this.$t('api_documentation')}
    },
    data: () => {
      return {
        html: ''
      }
    },
    methods: {
      async render() {
        let converter = new showdown.Converter();
        converter.setFlavor('github');
        let response = await axios.get(window.config.appUrl + '/apidocs');
        this.html = converter.makeHtml(response.data);
      }
    }
    ,
    mounted() {
      this.render();
    }
  }
</script>