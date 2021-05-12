<template>
  <div>
    <header id="inner-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="logo">
              <router-link class="color-white text-uppercase"
                           :to="{ name: 'welcome'}"
                           v-if="sitesettings.SITE_LOGO.file_url === null"
                           tag="a">
                <div v-if="sitesettings.SITE_NAME.value"><span class="text-400">{{ sitesettings.SITE_NAME.value }}</span></div>
                <div v-if="!sitesettings.SITE_NAME.value">EX<span class="text-400">BITA</span></div>
              </router-link>
              <router-link class="brand-image-heading"
                           :to="{ name: 'welcome'}"
                           v-else
                           tag="div">
                <img :src="sitesettings.SITE_LOGO.file_url"/>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </header>
    <section>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <child/>
          </div>
        </div>
      </div>
    </section>
    <footer id="inner-footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p>{{year}} © {{ sitesettings.SITE_URL.value }} - {{$t('all_rights_reserved')}}</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
  export default {
    name: 'singleform',
    data: function () {
      return {
        year: (new Date()).getFullYear()
      }
    }
  }
</script>