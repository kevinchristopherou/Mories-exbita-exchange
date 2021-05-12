import * as types from '../mutation-types'
import axios from 'axios'
import Cookies from "js-cookie";

const getDefaultLanguage = function () {
  return Cookies.get('lang.locale') || window.config.locale;
};

// state
export const state = {
  locale: getDefaultLanguage()
};

// mutations
export const mutations = {
  [types.SET_LOCALE](state, {locale}) {
    state.locale = locale;
    Cookies.set('lang.locale', locale, {expires: 730});
  },
};

// actions
export const actions = {
  async setLocale({state, commit, dispatch, getters}, payload) {
    try {
      if (getters.authCheck) {
        await axios.put('user/setlocale', payload);
      }
      commit(types.SET_LOCALE, payload);
    } catch (e) {
      return;
    }

    //reload page after setting locale in cookie.
    window.location.reload();
  }
};

// getters
export const getters = {
  getLocale: (state) => {
    return state.locale;
  }
};