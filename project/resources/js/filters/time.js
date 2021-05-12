import Vue from 'vue'
import * as moment from 'moment-timezone'

const tz = new Date().getTimezoneOffset();

/**
 * Converts the toIso8601String timestring into the users browser's default timezone in pretty date time format
 *
 * 2018-02-13T12:00:00+00:00 => 2018-02-13 15:00:00
 */
Vue.filter('tz_datetime', function (value) {
  if(!value) {
    return '-';
  }
  let date = moment(value);
  return date.format('DD/MM/YYYY HH:mm:ss');
});