<template>
  <div>
    <!--MOBILE LANGUAGE-->
    <div class="language-mobile dropdown d-md-none">
      <a class="nav-link text-uppercase" data-toggle="dropdown" href="javascript:void(0);">
        <icon name="language" class="font-size-1-5"></icon>&nbsp;&nbsp; {{getLocale}}
      </a>
      <div class="dropdown-menu dropdown-menu-right language">
        <ul class="dropdown-ul">
          <li v-for="locale in locales">
            <a href="#" v-on:click.prevent="setLocale({locale:locale.locale})">{{ locale.name }}</a>
          </li>
        </ul>
      </div>
    </div>

    <!--MOBILE LOGIN/USER SECTION -->
    <div class="btn-login d-md-none dropdown">
      <router-link v-if="!authenticated"
                   tag="button" type="button" class="btn btn-link"
                   :to="{ name: 'login' }">
        <i class="fa fa-sign-in" aria-hidden="true"></i>
      </router-link>
      <button v-if="authenticated" class="btn btn-link" data-toggle="dropdown">
        <i class="fa fa-user" aria-hidden="true"></i>
      </button>
      <div v-if="authenticated" class="dropdown-menu dropdown-menu-right">
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

    <!--MOBILE NAV TOGGLER-->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</template>

<script>
  export default {}
</script>