import Vue from 'vue'

Vue.filter('round', function(value, decimals) {
  //Check if value is a valid numeric value
  if(isNaN(parseFloat(value)) || !isFinite(value)) {
    return value;
  }

  if(!value) {
    value = 0;
  }

  if(!decimals) {
    decimals = 0;
  }

  // Set max 8 decimal size
  if(decimals > 8) {
    decimals = 8;
  }

  value = (Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals)).toFixed(decimals);
  return value;
});


Vue.filter('smartround', function(value, decimals) {
  if(!value) {
    value = 0;
  }

  if(!decimals) {
    decimals = 0;
  }

  //todo: implement this function
  if(decimals > 0) {
    let roundThreshold = Math.pow(10,decimals-1);
    let i = 1;
    let temp = value;
    while(temp < roundThreshold) {
      temp *= 10;
      i++;
    }
  }

  value = (Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals)).toFixed(decimals);
  return value;
});