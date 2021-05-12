<template>
  <footer id="footer">
    <div id="contact" class="footer-top">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 lang="en" class="text-uppercase">{{ sitesettings.SITE_NAME.value }}</h1>
            <ul class="social-link clearfix">
              <li v-if="sitesettings.SOCIAL_FACEBOOK_URL.value">
                <a :href="sitesettings.SOCIAL_FACEBOOK_URL.value">
                  <img src="/images/facebook.png" class="img-responsive" alt="Image"/>Facebook
                </a>
              </li>
              <li v-if="sitesettings.SOCIAL_TWITTER_URL.value">
                <a :href="sitesettings.SOCIAL_TWITTER_URL.value">
                  <img src="/images/twitter.png" class="img-responsive" alt="Image">Twitter
                </a>
              </li>
              <li v-if="sitesettings.SITE_CONTACT_EMAIL.value">
                <a :href="'mailto:'+ sitesettings.SITE_CONTACT_EMAIL.value">
                  <img src="/images/email.png" class="img-responsive" alt="Image">
                  {{ sitesettings.SITE_CONTACT_EMAIL.value }}
                </a>
              </li>

              <li>
                <router-link :to="{name: 'register' }" v-if="!authenticated">
                  <img src="/images/register.png" class="img-responsive" alt="Image">{{$t('register')}}
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p class="footer-bottom-left">
              {{year}} © {{ sitesettings.SITE_COPYRIGHT.value }} - {{$t('all_rights_reserved')}}
            </p>
            <div class="footer-bottom-right">
              <router-link :to="{name: 'terms'}">{{$t('terms_of_use')}}</router-link>
              <router-link :to="{name: 'security'}">{{$t('security')}}</router-link>
              <router-link :to="{name: 'apidocs'}">{{$t('api_documentation')}}</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
  export default {
    data: () => ({
      year: (new Date()).getFullYear()
    }),
  }
</script>
