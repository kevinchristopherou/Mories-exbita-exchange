import Vue from "vue";
import Toasted from "vue-toasted";

Vue.use(Toasted, {
  iconPack: 'fontawesome',
  position: "bottom-center",
  duration: 3000,
  maxItems: 4,
  action: {
    text: window.config.translations.dismiss,
    onClick: (e, toastObject) => {
      toastObject.goAway(0);
    }
  },
});
