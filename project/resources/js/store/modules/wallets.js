import Vue from 'vue'
import * as types from '../mutation-types'
import axios from 'axios'

// state
export const state = {
  wallets: []
};

// mutations
export const mutations = {
  [types.SET_WALLETS](state, {wallets}) {
    state.wallets = wallets;
  },
  [types.UPDATE_WALLET](state, {wallet}) {
    for (let x in state.wallets) {
      if (state.wallets[x].id === wallet.id) {
        Vue.set(state.wallets, x, wallet);
      }
    }
  },
};

// actions
export const actions = {
  async fetchWallets({state, commit}) {
    let response = await axios.get('wallets');
    commit(types.SET_WALLETS, {wallets: response.data.data});
  },
  updateWallet({state, commit}, payload) {
    commit(types.UPDATE_WALLET, payload);
  },
};

// getters
export const getters = {
  getWallets: (state) => {
    return state.wallets;
  },
  getNonNegativeWallets: (state) => {
    return _.filter(state.wallets, (wallet) => {
      return wallet.available > 0;
    });
  },
  getWalletById: (state) => (id) => {
    return state.wallets.find((wallet) => {
      return wallet.id === id;
    });
  },
  getWalletByCurrency: (state) => (currency_id) => {
    return state.wallets.find((wallet) => {
      return wallet.currency_id === currency_id;
    });
  }
};