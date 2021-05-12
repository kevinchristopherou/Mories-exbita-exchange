import * as types from '../mutation-types'
import axios from 'axios'

// state
export const state = {
  two_fa_error: null,
  smsSent: false,
  smsInterval: null,
  smsRequestError: null,
  smsTimeout: window.config.smsTimeout,
  smsShowResend: false
};

// mutations
export const mutations = {
  [types.SET_TWO_FA_ERROR](state, payload) {
    state.two_fa_error = payload;
  },

  [types.SET_TWO_FA_SMS_STATUS](state, payload) {
    for (let x in payload) {
      state[x] = payload[x];
    }
  },

  async [types.CLEAR_TWO_FA_SMS_INTERVAL](state) {
    if (state.smsInterval !== null) {
      clearTimeout(state.smsInterval);
      state.smsInterval = null;
    }
  },
  [types.TICK_TWO_FA_SMS_INTERVAL](state) {
    state.smsTimeout--;
  },
  [types.RESET_SMS_STATUS](state) {
    if (state.smsInterval !== null) {
      clearTimeout(state.smsInterval);
    }
    state.smsSent = false;
    state.smsInterval = null;
    state.smsRequestError = null;
    state.smsTimeout = window.config.smsTimeout;
    state.smsShowResend = false;
  }
};

// actions
export const actions = {

  async sendSms({state, commit, dispatch}, payload) {

    try {
      if (payload && payload.setup) {
        await axios.post('/twofa/setup/sms', {phone: payload.phone})
      } else {
        await axios.get('/twofa/request');
      }

      let interval = setInterval(() => {
        commit(types.TICK_TWO_FA_SMS_INTERVAL);
        if (state.smsTimeout === 0) {
          dispatch('clearSmsInterval');
        }
      }, 1000);

      payload = {
        smsSent: true,
        smsRequestError: false,
        smsTimeout: window.config.smsTimeout,
        smsInterval: interval
      }
    } catch (e) {
      payload = {
        smsSent: false,
        smsRequestError: e.response.data.message
      };

      //If sms timeout error is returned, lets set the sms timeout to that value
      if (e.response.data.message === 'sms_timeout_error') {
        dispatch('clearSmsInterval');

        let interval = setInterval(() => {
          commit(types.TICK_TWO_FA_SMS_INTERVAL);
          if (state.smsTimeout === 0) {
            dispatch('clearSmsInterval');
          }
        }, 1000);

        payload.smsTimeout = parseInt(e.response.data.errors.timeout);
        payload.smsInterval = interval;
        payload.smsSent = false;
      } else {
        commit(types.SET_TWO_FA_SMS_STATUS, payload);
        throw e;
      }
    }

    payload.smsShowResend = true;

    commit(types.SET_TWO_FA_SMS_STATUS, payload);
  },

  smsReset(context) {
    context.commit(types.RESET_SMS_STATUS);
  },

  async clearSmsInterval(context) {
    await context.commit(types.CLEAR_TWO_FA_SMS_INTERVAL);
  }
};

// getters
export const getters = {
  twoFaError: (state) => {
    return state.two_fa_error;
  },

  smsSent: (state) => {
    return state.smsSent;
  },
  smsTimeout: (state) => {
    return state.smsTimeout;
  },
  smsRequestError: (state) => {
    return state.smsRequestError;
  },
  smsShowResend: (state) => {
    return state.smsShowResend;
  }
};