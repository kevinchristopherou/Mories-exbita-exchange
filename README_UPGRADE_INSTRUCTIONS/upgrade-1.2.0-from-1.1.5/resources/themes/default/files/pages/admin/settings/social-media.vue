<template>
  <main class="whitebox" id="settings-page">
    <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.social_media') }} {{ $t('admin.site_settings.settings') }}</h3>

    <site-settings
        keys="SOCIAL_FACEBOOK_URL,SOCIAL_TWITTER_URL"></site-settings>
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
