import Vue from 'vue'
import * as types from '../mutation-types'
import axios from 'axios'

// state
export const state = {
  fiatWallets: []
};

// mutations
export const mutations = {
  [types.SET_FIATWALLETS](state, {fiatWallets}) {
    state.fiatWallets = fiatWallets;
  },
  [types.UPDATE_FIATWALLET](state, {fiatWallet}) {
    for (let x in state.fiatWallets) {
      if (state.fiatWallets[x].id === fiatWallet.id) {
        Vue.set(state.fiatWallets, x, fiatWallet);
      }
    }
  },
};

// actions
export const actions = {
  async fetchFiatWallets({state, commit}) {
    let response = await axios.get('fiatwallets');
    commit(types.SET_FIATWALLETS, {fiatWallets: response.data.data});
  },
  updateFiatWallet({state, commit}, payload) {
    commit(types.UPDATE_FIATWALLET, payload);
  },
};

// getters
export const getters = {
  getFiatWallets: (state) => {
    return state.fiatWallets;
  },
  getFiatWalletById: (state) => (id) => {
    return state.fiatWallets.find((fiatWallet) => {
      return fiatWallet.id === id;
    });
  },
  getFiatWalletByCurrency: (state) => (currency_id) => {
    return state.fiatWallets.find((fiatWallet) => {
      return fiatWallet.fiat_currency_id === currency_id;
    });
  }
};