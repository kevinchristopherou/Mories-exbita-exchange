<template>
  <div>
    <header id="header">
      <nav class="navbar navbar-expand-md navbar-light clearfix" id="welcome-navbar">
        <router-link class="navbar-brand" :to="{name: 'welcome'}">EX<span class="text-400">BITA</span>
        </router-link>
        <div class="language-mobile dropdown d-md-none">
          <a class="nav-link text-uppercase" data-toggle="dropdown" href="javascript:void(0);">
            <icon name="language" class="font-size-1-5"></icon>&nbsp;&nbsp; {{getLocale}}
          </a>
          <div class="dropdown-menu dropdown-menu-right language">
            <ul class="dropdown-ul">
              <li><a href="#" v-on:click.prevent="setLocale({locale:'tr'})">Türkçe</a></li>
              <li><a href="#" v-on:click.prevent="setLocale({locale:'en'})">English</a></li>
            </ul>
          </div>
        </div>

        <div class="btn-login d-md-none">
          <router-link tag="button" type="button" class="btn btn-link"
                       :to="{ name: 'login' }" v-if="!authenticated">
            <i class="fa fa-sign-in" aria-hidden="true"></i>
          </router-link>
          <router-link tag="button" class="btn btn-link"
                       :to="{ name: 'home' }" v-else>
            <i class="fa fa-user" aria-hidden="true"></i>
          </router-link>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link :to="{name:'welcome'}" class="nav-link">{{$t('home')}}</router-link>
            </li>
            <li class="nav-item">
              <router-link :to="{name:'markets'}" class="nav-link">{{$t('market.market')}}</router-link>
            </li>
            <li class="nav-item btn-login-out d-xs-none">
              <div class="btn-login">
                <router-link :to="{ name: 'login' }" v-if="!authenticated">
                  <button type="button" class="btn btn-link">{{$t('login')}}</button>
                </router-link>
                <router-link :to="{ name: 'home' }" v-else>
                  <button type="button" class="btn btn-link">
                    {{$t('my_account')}}
                    <span class="welcome_user_email">
                    ({{ user.email }})
                  </span>
                  </button>
                </router-link>
              </div>
            </li>
            <li class="nav-item language-out dropdown">
              <a class="nav-link text-uppercase" href="#" data-toggle="dropdown">
                &nbsp;<icon name="language" class="font-size-1-5"></icon> &nbsp;&nbsp{{getLocale}}
              </a>
              <div class="dropdown-menu dropdown-menu-right language">
                <ul class="dropdown-ul">
                  <li><a href="#" v-on:click.prevent="setLocale({locale:'tr'})">Türkçe</a></li>
                  <li><a href="#" v-on:click.prevent="setLocale({locale:'en'})">English</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!--CONTENT SLOT-->
    <section class="home-banner tiny">
    </section>
    <section>
      <child/>
    </section>
    <!--FOOTER -->
    <footer id="footer" class="mt-5">
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
                  <a :href="'mailto:'+sitesettings.SITE_CONTACT_EMAIL.value">
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
              <p class="footer-bottom-left">{{year}} © {{ sitesettings.SITE_COPYRIGHT.value }} - {{$t('all_rights_reserved')}}</p>
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
