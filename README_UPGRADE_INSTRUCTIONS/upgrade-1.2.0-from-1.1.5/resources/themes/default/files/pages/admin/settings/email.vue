<template>
  <main class="whitebox" id="settings-page">
    <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.email') }} {{ $t('admin.site_settings.settings') }}</h3>

    <site-settings
        keys="MAIL_DRIVER,MAIL_HOST,MAIL_PORT,MAIL_USERNAME,MAIL_PASSWORD,MAIL_FROM_ADDRESS,MAIL_FROM_NAME,MAIL_ENCRYPTION,MAIL_TEST_ADDRESS"></site-settings>
  </main>

</template>

<script>
  import SiteSettings from "./site-settings";

  export default {
    components: {
      SiteSettings,
      'site-settings': SiteSettings
    }
  }
</script>
