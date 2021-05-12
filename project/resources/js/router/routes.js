export default ({authGuard, guestGuard}) => [
  {
    path: '/',
    name: 'welcome',
    component: require('~/pages/welcome.vue').default
  },
  {
    path: '/home',
    name: 'home',
    component: require('~/pages/home.vue').default
  },
  {
    path: '/contact',
    name: 'contact',
    component: require('~/pages/contact.vue').default
  },
  {
    path: '/about',
    name: 'about',
    component: require('~/pages/about.vue').default
  },
  {
    path: '/cookies',
    name: 'cookies',
    component: require('~/pages/cookies.vue').default
  },
  {
    path: '/fees',
    name: 'fees',
    component: require('~/pages/fees.vue').default
  },
  {
    path: '/support',
    name: 'support',
    component: require('~/pages/support.vue').default
  },
  {
    path: '/privacy',
    name: 'privacy',
    component: require('~/pages/privacy.vue').default
  },
  {
    path: '/terms',
    name: 'terms',
    component: require('~/pages/terms.vue').default
  },
  {
    path: '/security',
    name: 'security',
    component: require('~/pages/security.vue').default
  },
  {
    path: '/apidocs',
    name: 'apidocs',
    component: require('~/pages/apidocs.vue').default
  },
  {
    path: '/markets',
    name: 'markets',
    component: require('~/pages/markets.vue').default
  },
  {
    path: '/market/:marketName',
    name: 'market.inner',
    component: require('~/pages/market-inner.vue').default
  },
  {
    path: '*',
    component: require('~/pages/errors/404.vue').default
  },

  // Authenticated routes.
  ...authGuard([
    //Exchange
    {
      path: '/orders',
      name: 'orders',
      component: require('~/pages/my_orders.vue').default
    },
    //Wallets
    {
      path: '/wallets',
      name: 'wallets',
      component: require('~/pages/wallets.vue').default
    },
    //Payments
    {
      path: '/payments/deposit/fiat',
      name: 'payments.deposit.fiat',
      component: require('~/pages/payments/deposit_fiat.vue').default
    },
    {
      path: '/payments/deposit/crypto',
      name: 'payments.deposit.crypto',
      component: require('~/pages/payments/deposit_crypto.vue').default
    },
    {
      path: '/payments/withdraw/fiat',
      name: 'payments.withdraw.fiat',
      component: require('~/pages/payments/withdraw_fiat.vue').default,
    },
    {
      path: '/payments/withdraw/crypto',
      name: 'payments.withdraw.crypto',
      component: require('~/pages/payments/withdraw_crypto.vue').default,
    },
    {
      path: '/settings/referral',
      name: 'settings.referral',
      component: require('~/pages/settings/referral.vue').default
    },
    {
      path: '/settings/password',
      name: 'settings.password',
      component: require('~/pages/settings/password.vue').default
    },
    {
      path: '/settings/two_fa_setup',
      name: 'settings.two_fa_setup',
      component: require('~/pages/settings/two_fa_setup.vue').default
    },
    {
      path: '/settings/personal_access_token',
      name: 'settings.personal_access_token',
      component: require('~/pages/settings/personal_access_token.vue').default
    },
    {
      path: '/settings/id_verification',
      name: 'settings.id_verification',
      component: require('~/pages/settings/id_verification.vue').default
    }
  ]),

  // Guest routes.
  ...guestGuard([
    {
      path: '/login',
      name: 'login',
      component: require('~/pages/auth/login.vue').default
    },
    {
      path: '/ipverify/:user_id/:token',
      name: 'ipverify',
      component: require('~/pages/auth/ipverify.vue').default
    },
    {
      path: '/user/:user_id/verify/:token',
      name: 'user.verify',
      component: require('~/pages/auth/verify.vue').default
    },
    {
      path: '/user/:user_id/deactivate/:token',
      name: 'user.deactivate',
      component: require('~/pages/auth/deactivate.vue').default
    },
    {
      path: '/two_fa',
      name: 'two_fa',
      component: require('~/pages/auth/two_fa.vue').default
    },
    {
      path: '/register',
      name: 'register',
      component: require('~/pages/auth/register.vue').default
    },
    {
      path: '/password/reset',
      name: 'password.request',
      component: require('~/pages/auth/password/email.vue').default
    },
    {
      path: '/password/reset/:user_id/:token',
      name: 'password.reset',
      component: require('~/pages/auth/password/reset.vue').default
    }
  ]),
]
