<template>
  <main class="whitebox" id="settings-page">
    <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.branding') }} {{
      $t('admin.site_settings.settings') }}</h3>

    <site-settings
        keys="SITE_NAME,SITE_URL,SITE_COPYRIGHT,SITE_CONTACT_EMAIL,SITE_LOGO,SITE_LOGO_INVERT,SITE_FAVICON"></site-settings>
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
