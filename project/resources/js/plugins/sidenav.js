jQuery(document).ready(function ($) {
  "use strict";
  $(document).on('click', "#secure-header .dropdown-toggle", function () {
    $(this).parent().toggleClass('show');
    $(this).next().toggleClass('show');
  });
});