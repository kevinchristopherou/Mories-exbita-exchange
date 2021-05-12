import Vue from "vue"

Vue.directive('button-loading', {
  update: function (el, binding, vnode) {
    //do nothing if value not changed
    if (binding.oldValue === binding.value) {
      return;
    }
    //
    if (binding.value === true) {

      if ($(el).find('.fa.fa-spinner.fa-spin').length === 0) {
        $(el).prepend('<i class="fa fa-spinner fa-spin mr-2"></i>');
      }

      $(el).attr('disabled', 'disabled');
    } else {
      $(el).find('.fa.fa-spinner.fa-spin').remove();
      $(el).removeAttr('disabled');
    }
  }
});