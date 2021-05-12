<template>
  <main class="whitebox" id="settings-page">
    <h3 class="title-h3 color-dark-blue text-center">{{ $t('admin.site_settings.aws-s3') }} {{ $t('admin.site_settings.settings') }}</h3>

    <site-settings
        keys="S3_KEY,S3_SECRET,S3_REGION,S3_BUCKET,S3_BUCKET_BACKUP"></site-settings>
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
