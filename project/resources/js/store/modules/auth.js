import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'
import * as moment from 'moment-timezone'

// state
export const state = {
  //auth status values
  user: null,
  two_fa_required: false,
  //token values
  remember: Cookies.get('auth.remember') === "true",
  token: Cookies.get('auth.access_token'),
  refreshToken: Cookies.get('auth.refresh_token'),
  tokenExpiresAt: moment(parseInt(Cookies.get('auth.tokenExpiresAt'))),
  tokenLifetime: parseInt(Cookies.get('auth.tokenLifetime')) || 0,
  tokenCheckInterval: null
};

// mutations
export const mutations = {
  [types.SAVE_TOKEN](state, {token, remember}) {
    state.token = token.access_token;
    state.remember = remember;
    state.refreshToken = token.refresh_token;

    let tokenDays = token.expires_in / (3600 * 24);
    state.tokenLifetime = token.expires_in;
    state.tokenExpiresAt = moment().add(token.expires_in, 'seconds');

    Cookies.set('auth.access_token', state.token, {expires: remember ? tokenDays : null})
    Cookies.set('auth.refresh_token', state.refreshToken, {expires: tokenDays * 2});
    Cookies.set('auth.remember', state.remember, {expires: tokenDays * 2});
    Cookies.set('auth.tokenExpiresAt', state.tokenExpiresAt.valueOf(), {expires: tokenDays * 2});
    Cookies.set('auth.tokenLifetime', state.tokenLifetime, {expires: tokenDays * 2});
  },


  [types.FETCH_USER_SUCCESS](state, {user}) {
    state.user = user;
  },

  [types.SET_TWO_FA_REQUIRED](state, {required}) {
    state.two_fa_required = required
  },

  [types.LOGOUT](state) {
    if (state.tokenCheckInterval) {
      clearInterval(state.tokenCheckInterval);
    }
    state.user = null;
    state.two_fa_required = false;

    state.token = null;
    state.remember = false;
    state.refreshToken = null;
    state.tokenExpiresAt = null;
    state.tokenLifetime = 0;
    state.tokenCheckInterval = null;
    Cookies.remove('auth.access_token');
    Cookies.remove('auth.refresh_token');
    Cookies.remove('auth.remember');
    Cookies.remove('auth.tokenExpiresAt');
    Cookies.remove('auth.tokenLifetime');
  },

  [types.UPDATE_USER](state, {user}) {
    state.user = user
  },

  [types.SET_TOKEN_CHECK_INTERVAL](state, payload) {
    state.tokenCheckInterval = payload.interval;
  },

  [types.CLEAR_TOKEN_CHECK_INTERVAL](state) {
    if (state.tokenCheckInterval) {
      clearInterval(state.tokenCheckInterval);
    }

    state.tokenCheckInterval = null;
  }
};

// actions
export const actions = {
  saveToken({commit, dispatch}, payload) {
    commit(types.SAVE_TOKEN, payload);
    dispatch('initTokenCheckInterval');
  },

  async refreshToken({commit, dispatch, state}) {
    if (state.refreshToken === null) {
      return;
    }

    try {
      let response = await axios.post('/user/refreshtoken', {refresh_token: state.refreshToken});
      commit(types.SAVE_TOKEN, {token: response.data, remember: state.remember});
      dispatch('initTokenCheckInterval');
    } catch (e) {
      console.log('error when refreshing token',e);
    }
  },

  async fetchUser({commit, getters, dispatch}) {
    try {
      const {data} = await axios.get('/user');
      commit(types.FETCH_USER_SUCCESS, {user: data});
      commit(types.SET_TWO_FA_REQUIRED, {required: false});

      //User has a different locale that the current one, set
      if (data.locale && data.locale !== getters.getLocale) {
        dispatch('setLocale', {locale: data.locale});
      }

      dispatch('fetchFiatWallets');
      dispatch('fetchWallets');
    } catch (e) {
      if (e.response.status === 401) {
        commit(types.LOGOUT)
      }
    }
  },

  updateUser({commit}, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout({commit}) {
    try {
      await axios.post('/user/logout')
    } catch (e) {
    }
    commit(types.LOGOUT);
    commit(types.RESET_SMS_STATUS);
    commit(types.SET_FIATWALLETS,{fiatWallets: []});
    commit(types.SET_WALLETS,{wallets: []});
  },

  initTokenCheckInterval({commit, state, dispatch}) {
    commit(types.CLEAR_TOKEN_CHECK_INTERVAL);

    if (state.remember && state.token && state.tokenLifetime > 0) {
      //let checkIntervalDuration = 2000; // 2 seconds, testing
      let checkIntervalDuration = 1000 * 60 * 10; // 10 minutes

      let tokenRemainingLife = state.tokenExpiresAt.diff(moment(), 'seconds');
      if ((tokenRemainingLife / state.tokenLifetime) < 0.5) {
        dispatch('refreshToken');
      }

      let tokenCheckInterval = setInterval(() => {
        let tokenRemainingLife = state.tokenExpiresAt.diff(moment(), 'seconds');
        if ((tokenRemainingLife / state.tokenLifetime) < 0.5) {
          dispatch('refreshToken');
        }
      }, checkIntervalDuration); //10 minutes check interval

      commit(types.SET_TOKEN_CHECK_INTERVAL, {interval: tokenCheckInterval});
    }
  }
};

// getters
export const getters = {
  authUser: state => state.user,
  authToken: state => state.token,
  authCheck: state => (state.user !== null && !state.two_fa_required),
  //if a user has a role, he can see the admin menu
  adCheck: state => (state.user !== null && !state.two_fa_required && state.user.roles.length > 0),
  authTwoFaRequired: state => state.two_fa_required,
};