<template>
  <footer class="footer-new">
    <div class="footer-main">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-4">
            <div class="footer-block">
              <h6>{{$t('new_home_page.footer.menu1')}}</h6>
              <ul>
                <li><router-link :to="{name: 'fees'}">{{$t('fees')}}</router-link></li>
                <li><router-link :to="{name: 'terms'}">{{$t('terms_of_use')}}</router-link></li>
                <li><router-link :to="{name: 'security'}">{{$t('security')}}</router-link></li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="footer-block">
              <h6>{{$t('new_home_page.footer.menu2')}}</h6>
              <ul>
                <li><router-link :to="{name:'markets'}" class="nav-link">{{$t('market.markets')}}</router-link></li>
                <li><router-link :to="{name: 'contact'}">{{$t('contact_us')}}</router-link></li>
                <li><router-link :to="{name: 'apidocs'}">{{$t('api_documentation')}}</router-link></li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="footer-block">
              <h6>{{$t('new_home_page.footer.menu3')}}</h6>
              <ul>
                <li v-if="sitesettings.SOCIAL_FACEBOOK_URL.value">
                  <a :href="sitesettings.SOCIAL_FACEBOOK_URL.value">
                    Facebook
                  </a>
                </li>
                <li v-if="sitesettings.SOCIAL_TWITTER_URL.value">
                  <a :href="sitesettings.SOCIAL_TWITTER_URL.value">
                    Twitter
                  </a>
                </li>
                <li v-if="sitesettings.SITE_CONTACT_EMAIL.value">
                  <a :href="'mailto:'+ sitesettings.SITE_CONTACT_EMAIL.value">
                    {{ sitesettings.SITE_CONTACT_EMAIL.value }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-4">
            <router-link v-if="sitesettings.SITE_LOGO.file_url" :to="{ name: 'home'}">
              <img :src="sitesettings.SITE_LOGO.file_url">
            </router-link>
          </div>
          <div class="col-12 col-md-4">
            <div class="copyrighttext">
              © {{ sitesettings.SITE_COPYRIGHT.value }}
            </div>
          </div>
        </div>
      </div>
    </div>

  </footer>
  <!--
  <footer id="footer">
    <div id="contact" class="footer-top">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 lang="en" class="text-uppercase">{{ sitesettings.SITE_NAME.value }}</h1>
            <ul class="social-link clearfix">

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
  -->
</template>

<script>
  export default {
    data: () => ({
      year: (new Date()).getFullYear()
    }),
  }
</script>
