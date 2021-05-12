import * as types from '../mutation-types'

// state
export const state = {
  socketId: null
};

// mutations
export const mutations = {
  [types.SET_SOCKET_ID](state, {socketId}) {
    state.socketId = socketId;
  },
};

// actions
export const actions = {
  async setSocketId({state, commit}, payload) {
    commit(types.SET_SOCKET_ID, payload);
  }
};

// getters
export const getters = {
  getSocketId: (state) => {
    return state.socketId;
  }
};