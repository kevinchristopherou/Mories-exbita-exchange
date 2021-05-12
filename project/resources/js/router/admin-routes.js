export default ({authGuard, guestGuard, aGuard}) => [
  ...aGuard([
    {
      path: '/admin',
      name: 'ahome',
      component: require('~/pages/admin/index.vue').default,
      children: [
        {
          path: '/adashboard',
          name: 'adashboard',
          component: require('~/pages/admin/dashboard.vue').default
        },
        {
          path: '/admin/users',
          name: 'admin.users',
          component: require('~/pages/admin/users.vue').default
        },
        {
          path: '/admin/verifications',
          name: 'admin.verifications',
          component: require('~/pages/admin/verifications.vue').default
        },
        //Payments
        {
          path: '/admin/payments/deposit/fiat',
          name: 'admin.payments.deposit.fiat',
          component: require('~/pages/admin/payments/deposit_fiat.vue').default
        },
        {
          path: '/admin/payments/deposit/crypto',
          name: 'admin.payments.deposit.crypto',
          component: require('~/pages/admin/payments/deposit_crypto.vue').default
        },
        {
          path: '/admin/payments/withdraw/fiat',
          name: 'admin.payments.withdraw.fiat',
          component: require('~/pages/admin/payments/withdraw_fiat.vue').default,
        },
        {
          path: '/admin/payments/withdraw/crypto',
          name: 'admin.payments.withdraw.crypto',
          component: require('~/pages/admin/payments/withdraw_crypto.vue').default,
        },
        //Exchange Settings
        {
          path: '/admin/exchange-settings/markets',
          name: 'admin.exchange_settings.markets',
          component: require('~/pages/admin/exchange-settings/markets.vue').default
        },
        {
          path: '/admin/exchange-settings/commission',
          name: 'admin.exchange_settings.commission',
          component: require('~/pages/admin/exchange-settings/commission.vue').default
        },
        {
          path: '/admin/exchange-settings/currencies',
          name: 'admin.exchange_settings.currencies',
          component: require('~/pages/admin/exchange-settings/currencies.vue').default
        },
        {
          path: '/admin/exchange-settings/fiat-currencies',
          name: 'admin.exchange_settings.fiat_currencies',
          component: require('~/pages/admin/exchange-settings/fiat-currencies.vue').default
        },
        //Site Settings
        {
          path: '/admin/settings/branding',
          name: 'admin.settings.branding',
          component: require('~/pages/admin/settings/branding.vue').default
        },
        {
          path: '/admin/settings/email',
          name: 'admin.settings.email',
          component: require('~/pages/admin/settings/email.vue').default
        },
        {
          path: '/admin/settings/aws-s3',
          name: 'admin.settings.aws-s3',
          component: require('~/pages/admin/settings/aws-s3.vue').default
        },
        {
          path: '/admin/settings/sms-provider',
          name: 'admin.settings.sms-provider',
          component: require('~/pages/admin/settings/sms-provider.vue').default
        },
        {
          path: '/admin/settings/nocaptcha',
          name: 'admin.settings.nocaptcha',
          component: require('~/pages/admin/settings/nocaptcha.vue').default
        },
        {
          path: '/admin/settings/bitcoind',
          name: 'admin.settings.bitcoind',
          component: require('~/pages/admin/settings/bitcoind.vue').default
        },
        {
          path: '/admin/settings/ethereum',
          name: 'admin.settings.ethereum',
          component: require('~/pages/admin/settings/ethereum.vue').default
        },
        {
          path: '/admin/settings/coinpayments',
          name: 'admin.settings.coinpayments',
          component: require('~/pages/admin/settings/coinpayments.vue').default
        },
        {
          path: '/admin/settings/social-media',
          name: 'admin.settings.social_media',
          component: require('~/pages/admin/settings/social-media.vue').default
        },
        //Health Checker
        {
          path: '/admin/health-checker',
          name: 'admin.health_checker',
          component: require('~/pages/admin/health-checker/health-checker.vue').default
        },
        //Theme Editor
        {
          path: '/admin/theme-editor',
          name: 'admin.theme_editor',
          component: require('~/pages/admin/theme-editor/file-list.vue').default
        },
        //Referral System
        {
          path: '/admin/referral',
          name: 'admin.referral',
          component: require('~/pages/admin/referrals.vue').default
        },
        //Language Editor
        {
          path: '/admin/locale',
          name: 'admin.locale',
          component: require('~/pages/admin/locale/locale.vue').default
        },
        {
          path: '/admin/locale/:locale',
          name: 'admin.locale.edit',
          component: require('~/pages/admin/locale/edit.vue').default
        },
      ]
    },

  ]),
]
