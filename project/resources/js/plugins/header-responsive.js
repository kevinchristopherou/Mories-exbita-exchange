global.setHeaderStatus = function () {
  "use strict";
  //Do nothing if no header exists.
  if ($("#header").length === 0) {
    return;
  }
  if ($("#header").offset().top > 50) {
    $("#header").addClass("header-fixed");
  } else {
    $("#header").removeClass("header-fixed");
  }
};

jQuery(document).ready(function ($) {
  "use strict";

  //jQuery to collapse the navbar on scroll
  $(window).scroll(function () {
    setHeaderStatus();
  });

  setHeaderStatus();

  //jQuery for page scrolling feature - requires jQuery Easing plugin
  $('body').on('click', 'a.page-scroll', function (event) {
    "use strict";
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: $($anchor.attr('data-href')).offset().top
    }, 1500, 'easeInOutExpo');
    event.preventDefault();
  });

  // --- Scroll Ul Main - Solution Page
  //==================================================
  $(document).on('click', "#collapsibleNavbar a", function () {
    "use strict";
    $("#collapsibleNavbar a").removeClass("in");
  });
});
