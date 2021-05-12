import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import store from '~/store'
import routerFactory from '~/router'
import {i18n, io} from '~/plugins'
import '~/filters'
import App from '~/components/App'
import mixin from '~/mixin'
import '~/components'
import '~/directives'

window.io = io;

Vue.config.strict = true;
Vue.config.productionTip = false;
Vue.mixin(mixin);
Vue.use(BootstrapVue);

const noRoutes = function () {
  return []
};

export default function (extraRoutes = noRoutes) {

  const router = routerFactory(extraRoutes);

  new Vue({
    i18n,
    store,
    router,
    ...App
  });

  const EventBus = new Vue();

  Object.defineProperties(Vue.prototype, {
    $bus: {
      get: function () {
        return EventBus
      }
    }
  })
}
