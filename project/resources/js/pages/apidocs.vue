<template>
  <div class="section-tiny">
    <div class="inner-title">
      <h1>{{$t('api_documentation')}}</h1>
    </div>
    <section class="main-content">
      <div class="container">
        <div class="maintext-block">
          <div class="text-center" v-if="html === ''">
            <icon class="spinner" spin scale="4x"></icon>
          </div>
          <div v-html="html">

          </div>
        </div>
      </div>
    </section>
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
