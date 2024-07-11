// resources/js/store/index.js

import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    user_id: null,
    api_token: null,
  },
  mutations: {
    setUser(state, user_id) {
      state.user_id = user_id;
    },
    setApiToken(state, api_token) {
      state.api_token = api_token;
    },
    clearAuth(state) {
      state.user_id = null;
      state.api_token = null;
    },
  },
  actions: {
    // Actions for login, logout, etc.
  },
  modules: {
    // Other Vuex modules if needed
  },
});
