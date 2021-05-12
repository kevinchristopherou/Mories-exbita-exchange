import './axios'
import 'bootstrap'
import 'bootstrap-table'
import 'jquery-easing'
import 'moment'
import './header-responsive'
import './sidenav'
import './toasted'
import i18n from './vue-i18n'
import io from 'socket.io-client'

moment.locale(window.config.locale);

export {i18n, io}
