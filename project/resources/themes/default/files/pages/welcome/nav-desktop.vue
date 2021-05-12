<template>
  <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">

    <ul class="navbar-nav" id="welcome-navbar">

      <!--DESKTOP LINKS-->
      <li class="nav-item">
        <a class="nav-link page-scroll" href="#" data-href="#about">{{$t('about_us')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link page-scroll" href="#" data-href="#currencies">{{$t('currencies')}}</a>
      </li>
      <li class="nav-item">
        <router-link :to="{name: 'contact'}" class="nav-link">
          {{$t('contact_us')}}
        </router-link>
      </li>

      <!--DESKTOP LOGIN/USER SECTION-->
      <li class="nav-item btn-login-out d-xs-none dropdown">
        <!--LOGIN BUTTON-->
        <div v-if="!authenticated" class="btn-login">
          <router-link :to="{ name: 'login' }">
            <button type="button" class="btn btn-link">{{$t('login')}}</button>
          </router-link>
        </div>

        <!--DROPDOWN IF LOGGED IN-->
        <div v-else class="btn-login" data-toggle="dropdown">
          <button class="btn btn-link">
            <i class="fa fa-user"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <ul class="dropdown-ul">
              <li>
                <router-link :to="{ name: 'home' }">
                  {{$t('market.market')}}
                  <icon name="bitcoin" class="float-right"></icon>
                </router-link>
              </li>
              <li>
                <a href="#" v-on:click.prevent="logout">
                  {{$t('logout')}}
                  <icon name="sign-out" class="float-right"></icon>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </li>

      <!--DESKTOP LANGUAGE SELECTION-->
      <li class="nav-item language-out dropdown">
        <a class="nav-link text-uppercase" href="#" data-toggle="dropdown">
          &nbsp;<icon name="language" class="font-size-1-5"></icon>
          &nbsp{{getLocale}}
        </a>
        <div class="dropdown-menu dropdown-menu-right language">
          <ul class="dropdown-ul">
            <li v-for="locale in locales">
              <a href="#" v-on:click.prevent="setLocale({locale:locale.locale})">{{ locale.name }}</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {}
</script>
