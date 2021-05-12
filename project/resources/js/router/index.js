import Vue from 'vue'
import store from '~/store'
import Meta from 'vue-meta'
import routes from './routes'
import Router from 'vue-router'
import {sync} from 'vuex-router-sync'

Vue.use(Meta)
Vue.use(Router)

export default function (extraRoutes) {
  const router = make(
    routes({authGuard, guestGuard}).concat(extraRoutes({authGuard, guestGuard, aGuard}))
  );

  window.router = router;
  sync(store, router);

  return router;
}

/**
 * Create a new router instance.
 *
 * @param  {Array} routes
 * @return {Router}
 */
function make(routes) {

  const router = new Router({
    routes,
    scrollBehavior,
    mode: 'hash',
    linkActiveClass: 'active'
  });

  // Register before guard.
  router.beforeEach(async (to, from, next) => {

    // console.log("===================");
    // console.log('route from', from.name);
    // console.log('route before each', to.name);

    if (to.name === 'welcome' && window.location.pathname === '/a/') {
      next({path: '/adashboard'});
    }
    else {
      setLayout(to);
      //If the authToken exists but auth user is not defined in store, we need to fetch the user data
      if (!store.getters.authCheck && store.getters.authToken) {
        if (!store.getters.authTwoFaRequired) {
          try {
            await store.dispatch('fetchUser');
          } catch (e) {
          }
        }
      }

      next();
    }
  });

  // Register after hook.
  router.afterEach((to, from) => {
    router.app.$nextTick(() => {
      router.app.$loading.finish();
      //Collapse mobile navbar on mobile after route change.
      if (window.innerWidth <= 992) {
        $('#navbarNav').removeClass("show");
      }
      setHeaderStatus();
    })
  });

  return router;
}

/**
 * Set the application layout from the matched page component.
 *
 * @param {Router} router
 * @param {Route} to
 */
function setLayout(to) {
  // Get the first matched component.
  const [component] = window.router.getMatchedComponents({...to})

  if (component) {
    window.router.app.$nextTick(() => {
      // Start the page loading bar.
      if (component.loading !== false) {
        window.router.app.$loading.start()
      }
	  
      // Set application layout.
      window.router.app.setLayout(component.layout || '')
    })
  }
}

/**
 * Redirect to login if guest.
 *
 * @param  {Array} routes
 * @return {Array}
 */
function authGuard(routes) {
  return beforeEnter(routes, (to, from, next) => {
    if (!store.getters.authCheck) {
      next({name: 'login'})
      window.router.app.$nextTick(() => {
        window.router.app.setLayout('singleform');
      })
    } else {
      next()
    }
  })
}

/**
 * Redirect to home if not admin.
 *
 * @param  {Array} routes
 * @return {Array}
 */
function aGuard(routes) {
  return beforeEnter(routes, (to, from, next) => {
    if (!store.getters.adCheck) {
      next({name: 'home'})
      window.router.app.$nextTick(() => {
        window.router.app.setLayout('market');
      })
    } else {
      next()
    }
  })
}

/**
 * Redirect home if authenticated.
 *
 * @param  {Array} routes
 * @return {Array}
 */
function guestGuard(routes) {
  return beforeEnter(routes, (to, from, next) => {
    if (store.getters.authCheck) {
      next({name: 'home'});
      window.router.app.$nextTick(() => {
        window.router.app.setLayout('market');
      })
    } else {
      next()
    }
  })
}

/**
 * Apply beforeEnter guard to the routes.
 *
 * @param  {Array} routes
 * @param  {Function} beforeEnter
 * @return {Array}
 */
function beforeEnter(routes, beforeEnter) {
  return routes.map(route => {
    return {...route, beforeEnter}
  })
}

/**
 * @param  {Route} to
 * @param  {Route} from
 * @param  {Object|undefined} savedPosition
 * @return {Object}
 */
function scrollBehavior(to, from, savedPosition) {
  return { x: 0, y: 0 };
  //
  // if (savedPosition) {
  //   return savedPosition
  // }
  //
  // const position = {}
  //
  // if (to.hash) {
  //   position.selector = to.hash
  // }
  //
  // if (to.matched.some(m => m.meta.scrollToTop)) {
  //   position.x = 0
  //   position.y = 0
  // }
  //
  // return position
}
