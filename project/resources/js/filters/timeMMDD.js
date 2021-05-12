import Vue from 'vue'

Vue.filter('timeMMDD', function (value) {
  let minutes = parseInt(Math.floor(value / 60));
  let seconds = parseInt(value % 60);

  let dMins = (minutes > 9 ? minutes : '0' + minutes);
  let dSecs = (seconds > 9 ? seconds : '0' + seconds);

  return dMins + ":" + dSecs;
});