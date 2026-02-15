(function($) {
  'use strict';
  
  $(document).ready(function() {
    // Inisialisasi variabel
    var body = $('body');
    var sidebar = $('.sidebar');
    
    // Fungsi untuk active class
    function addActiveClass(element) {
      var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
      
      if (current === "") {
        if (element.attr('href').indexOf("index.html") !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
        }
      } else {
        if (element.attr('href').indexOf(current) !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
        }
      }
    }
    
    // Apply active class to menu items
    $('.nav li a').each(function() {
      addActiveClass($(this));
    });
    
    // Toggle sidebar minimize
    $('[data-toggle="minimize"]').on("click", function() {
      body.toggleClass('sidebar-icon-only');
    });
    
    // Fullscreen toggle
    $("#fullscreen-button").on("click", function() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
          console.log(`Error attempting to enable fullscreen: ${err.message}`);
        });
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        }
      }
    });
    
    // Banner cookie
    var proBanner = $('#proBanner');
    if (proBanner.length) {
      if ($.cookie('corona-free-banner') !== "true") {
        proBanner.addClass('d-flex');
      } else {
        proBanner.addClass('d-none');
      }
      
      $('#bannerClose').on('click', function() {
        proBanner.removeClass('d-flex').addClass('d-none');
        var date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
        $.cookie('corona-free-banner', "true", { expires: date });
      });
    }
  });
})(jQuery);