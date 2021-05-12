<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 text-left" v-html="html"></div>
      <div class="col-12 text-center" v-if="html===''">
        <icon name="spinner" spin scale="4x"></icon>
      </div>
    </div>
  </div>
</template>

<script>
  import showdown from "showdown"
  import axios from "axios"

  export default {
    name: 'terms-content',
    data: () => {
      return {
        html: ''
      }
    },
    methods: {
      async render() {
        let converter = new showdown.Converter();
        converter.setFlavor('github');
        let response = await axios.get(window.config.appUrl + '/terms');
        this.html = converter.makeHtml(response.data);
      }
    }
    ,
    mounted() {
      this.render();
    }
  }
</script>