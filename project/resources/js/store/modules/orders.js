import Vue from 'vue'
import axios from 'axios'
import * as types from '../mutation-types'
import * as _ from 'lodash'

// state
export const state = {
  orders: []
};

// mutations
export const mutations = {
  [types.SET_ORDERS](state, {orders}) {
    // console.log(orders);
    state.orders = orders;
  },

  [types.UPDATE_ORDER](state, {order}) {

    //Try to find the order in the orders array,
    let exists = false;
    for (let x in state.orders) {
      if (state.orders[x].uuid === order.uuid) {
        exists = true;
        //Update it if found
        Vue.set(state.orders, x, order);
      }
    }
    //If it is not found, push it into array.
    if (!exists) {
      state.orders.push(order);
    }
  },
  [types.REMOVE_ORDER](state, {market, order}) {
    for (let x in state.orders) {
      if (state.orders[x].uuid === order.uuid) {
        Vue.delete(state.orders, x);
      }
    }
  }
};

// actions
export const actions = {
  async loadOrders({state, getters, rootGetters, commit, dispatch}) {
    try {
      let response = await axios.get('orders');
      commit(types.SET_ORDERS, {orders: response.data.data});

      //After loading orders, make sure that orderbook is refreshed to be able to show cancel buttons according to the order uuids.
      let refreshedMarkets = {};
      //orderbook of each market in which the user has an open order should be refreshed
      _.each(state.orders, (order) => {

        if (order.market in refreshedMarkets) {
          return;
        }

        dispatch('refreshMarketOrders', {marketName: order.market});
        refreshedMarkets[order.market] = true;
      })
    }
    catch (e) {
      console.log(e);
    }
  },
  clearOrders({state, commit}) {
    commit(types.SET_ORDERS, {orders: []});
  },

  updateOrder({state, commit}, payload) {
    commit(types.UPDATE_ORDER, payload);
  },

  removeOrder({state, commit}, payload) {
    commit(types.REMOVE_ORDER, payload);
  },
};

// getters
export const getters = {
  getMyOrdersByMarket: (state) => (market) => {
    let result = state.orders;

    if (market !== null) {
      result = _.filter(state.orders, (order) => {
        return order.market === market;
      });
    }

    result = _.sortBy(result, 'created_at');
    _.reverse(result);
    return result;
  },

  getOrderUuids: (state) => {
    return _.map(state.orders, 'uuid');
  }
};