import Vue from 'vue'
import * as types from '../mutation-types'
import axios from 'axios'
import * as _ from 'lodash'

// state
export const state = {
  markets: [],
  orderbook: {},
  stateid: '',
};

// mutations
export const mutations = {

  [types.SET_MARKETS](state, {markets}) {
    state.markets = markets;
  },

  [types.UPDATE_MARKET](state, {market}) {
    for (let x in state.markets) {
      if (state.markets[x].name === market.name) {
        // console.log("update market");
        //do not lose the orders of existing market object.
        market.orders = state.markets[x].orders;
        market.history = state.markets[x].history;
        Vue.set(state.markets, x, market);
      }
    }
  },

  [types.SET_MARKET_ORDERS](state, {market, orders}) {

    let data = {
      'buy': {},
      'sell': {}
    };

    _.each(orders.buy, function (order) {
      data.buy[order.uuid] = order;
    });

    _.each(orders.sell, function (order) {
      data.sell[order.uuid] = order;
    });

    Vue.set(market, 'orders', data);
  },

  [types.SET_MARKET_ORDER](state, {market, order}) {
    // console.log("set order", order);
    if ('orders' in market === false) {
      console.log('orders not ready');
      return;
    }
    if (order.type === "SELL_LIMIT") {
      Vue.set(market.orders.sell, order.uuid, order);
      // orders = market.orders.sell;
    }
    else if (order.type === "BUY_LIMIT") {
      Vue.set(market.orders.buy, order.uuid, order);
    }
  },

  [types.REMOVE_MARKET_ORDER](state, {market, order}) {
    if ('orders' in market === false) {
      console.log('orders not ready');
      return;
    }

    // console.log("remove order", order);
    if (order.type === "SELL_LIMIT") {
      Vue.delete(market.orders.sell, order.uuid);
    }
    else if (order.type === "BUY_LIMIT") {
      Vue.delete(market.orders.buy, order.uuid);
    }
  },

  [types.PUSH_MARKET_HISTORY](state, {market, transaction}) {
    if ('history' in market === false) {
      console.log('history not ready');
      return;
    }

    if (!market.history) {
      Vue.set(market, 'history', []);
    }

    market.history.unshift(transaction);
    //Only keep last 100 history entries, remove the rest.
    Vue.set(market, 'history', market.history.slice(0, 100));
  },

  [types.SET_MARKET_HISTORY](state, {market, history}) {
    Vue.set(market, 'history', history);
  },

  [types.REFRESH_ORDERBOOK](state, {market, type, userOrderUuids}) {

    // var t0 = performance.now();

    if (!(market.name in state.orderbook)) {
      state.orderbook[market.name] = {};
      state.orderbook[market.name].sell = [];
      state.orderbook[market.name].buy = [];
    }

    if (!market.orders) {
      state.orderbook[market.name][type] = [];
      return;
    }

    let orders = _.map(type === 'sell' ? market.orders.sell : market.orders.buy, item => {
      // lodash clonse is 5x slower than manual clone.
      // return _.clone(item);
      return {
        quantity: parseFloat(item.quantity),
        rate: item.rate,
        type: item.type,
        uuid: item.uuid
      }
    });

    //Group the orders by rate, so we display one row for each distinct rate.
    let grouped = _.groupBy(orders, 'rate');

    grouped = _.sortBy(grouped, function (group) {
      if (type === 'sell') {
        return group[0].rate;
      }
      else {
        return group[0].rate * -1;
      }
    });

    let result = [];
    let sum = 0;


    _.each(grouped, (group) => {
      let rate = group[0].rate;
      //total quantity of orders for this rate
      let quantity = _.sumBy(group, 'quantity');

      //total fiat value of orders for this rate
      let total = quantity * rate;

      //we pluck the quantities as a sub array, display on hover tooltip
      let quantities = _.map(group, 'quantity');

      let uuids = _.map(_.filter(group, (item) => {
        return userOrderUuids.indexOf(item.uuid) !== -1;
      }), 'uuid');

      sum += total;

      result.push({
        rate,
        quantity,
        total,
        sum,
        quantities,
        uuids
      });
    });

    // var t1 = performance.now();
    // console.log("UpdateOrderBook took " + (t1 - t0) + " ms.");

    state.orderbook[market.name][type] = result;

    Vue.set(state, 'stateid', Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15));
  }
};

// actions
export const actions = {
  async loadMarkets({state, commit}) {
    try {
      let response = await axios.get('markets');
      commit(types.SET_MARKETS, {markets: response.data.data});
    }
    catch (e) {
      console.log(e);
    }
  },
  updateMarket({state, commit}, payload) {
    commit(types.UPDATE_MARKET, payload);
  },

  refreshMarketOrders({state, commit, rootGetters, getters}, payload) {
    // console.log('refreshMarketOrders', payload);
    let market = getters.getMarketByName(payload.marketName);

    commit(types.REFRESH_ORDERBOOK, {market: market, type: 'sell', userOrderUuids: rootGetters.getOrderUuids})
    commit(types.REFRESH_ORDERBOOK, {market: market, type: 'buy', userOrderUuids: rootGetters.getOrderUuids})
  },

  setMarketOrders({state, commit, rootGetters}, payload) {
    // console.log('setMarketOrders', payload);
    commit(types.SET_MARKET_ORDERS, payload);
    commit(types.REFRESH_ORDERBOOK, {...payload, type: 'sell', userOrderUuids: rootGetters.getOrderUuids})
    commit(types.REFRESH_ORDERBOOK, {...payload, type: 'buy', userOrderUuids: rootGetters.getOrderUuids})
  },

  setMarketOrder({state, commit, rootGetters}, payload) {
    // console.log('setMarketOrder', payload);
    commit(types.SET_MARKET_ORDER, payload);
    let type = payload.order.type === 'SELL_LIMIT' ? 'sell' : 'buy';
    commit(types.REFRESH_ORDERBOOK, {...payload, type, userOrderUuids: rootGetters.getOrderUuids})
  },

  removeMarketOrder({state, commit, rootGetters}, payload) {
    // console.log('removeMarketOrders', payload);
    commit(types.REMOVE_MARKET_ORDER, payload);
    let type = payload.order.type === 'SELL_LIMIT' ? 'sell' : 'buy';
    commit(types.REFRESH_ORDERBOOK, {...payload, type, userOrderUuids: rootGetters.getOrderUuids})
  },

  setMarketHistory({state, commit}, payload) {
    commit(types.SET_MARKET_HISTORY, payload);
  },

  pushMarketHistory({state, commit}, payload) {
    commit(types.PUSH_MARKET_HISTORY, payload);
  },
};

// getters
export const getters = {

  getMarkets: (state) => {
    return state.markets;
  },

  getMarketById: (state) => (id) => {
    return state.markets.find((market) => {
      return market.id === id;
    });
  },

  getMarketByName: (state) => (name) => {
    return state.markets.find((market) => {
      return market.name === name;
    });
  },

  getMarketByCurrencyId: (state) => (currency_id, fiat_currency_id) => {
    return state.markets.find((market) => {
      return market.currency_id === currency_id && market.fiat_currency_id === fiat_currency_id;
    });
  },

  getMarketOrderBook: (state, rootGetters) => (name, type) => {
    // console.log('getMarketOrderBook');
    if (state.stateid === '') {
      return [];
    }
    if (name in state.orderbook && type in state.orderbook[name]) {
      return state.orderbook[name][type];
    }

    return [];
  },

  getMarketHistory: (state) => (name) => {
    // console.log("get market history", name);
    let market = state.markets.find((market) => {
      return market.name === name;
    });

    if (!market.history) {
      return [];
    }

    return market.history;
  }
};
