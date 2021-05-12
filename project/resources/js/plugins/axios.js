"use strict";

import axios from 'axios'
import store from '~/store'
import swal from 'sweetalert2'
import * as types from '../store/mutation-types'

axios.defaults.baseURL = window.config.baseUrl;

axios.interceptors.request.use(request => {
  if (store.getters.authToken) {
    request.headers.common['Authorization'] = `Bearer ${store.getters.authToken}`
  }

  // request.headers['X-Socket-Id'] = Echo.socketId()

  return request
})

axios.interceptors.response.use(response => response, error => {
  const {status, data} = error.response

  if (status == 412) {
    store.commit(types.SET_TWO_FA_ERROR, data);
    store.commit(types.SET_TWO_FA_REQUIRED, {required: true})
    window.router.push({name: 'two_fa'})
  }

  if (status >= 500) {
    swal({
      type: 'error',
      title: swal.i18n.t('error_alert_title'),
      text: swal.i18n.t('error_alert_text')
    })
  }

  if (status === 401 && store.getters.authCheck) {
    swal({
      type: 'warning',
      title: swal.i18n.t('token_expired_alert_title'),
      text: swal.i18n.t('token_expired_alert_text')
    })
      .then(async () => {
        await store.dispatch('logout');
        window.router.push('login')
      })
  }

  /*if (status == 403) {
    swal({
      type: 'warning',
      title: 'Warning',
      text: 'This version of the application is just a demo. Only creating orders are available.'
    })
  }*/

  return Promise.reject(error)
})
