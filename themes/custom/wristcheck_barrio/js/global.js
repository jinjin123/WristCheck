/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.bootstrap_barrio_subtheme = {
    attach: function (context, settings) {
      var position = $(window).scrollTop();
      $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
          $('body').addClass("scrolled");
        } else {
          $('body').removeClass("scrolled");
        }
        var scroll = $(window).scrollTop();
        if (scroll > position) {
          $('body').addClass("scrolldown");
          $('body').removeClass("scrollup");
        } else {
          $('body').addClass("scrollup");
          $('body').removeClass("scrolldown");
        }
        position = scroll;
      });

    }
  };

  $(function () {
    //faq index
   $(".view-content").removeClass("row");
   $(".card-body").css("display","none");
    //faq dropdown
    $(".path-faq .view-wristcheck-faq .views-field-body .field-content .card .card-header  h2 div span i").click(function () {
      var target = $(this);
      // console.log(target.parent().parent().parent().parent().parent().parent().children().children().children()[1])
      if (target.parent().parent().parent().parent().parent().parent().children().children().children()[1].style.display == "block") {
        target.parent().parent().parent().parent().parent().parent().children().children().children()[1].style.display = "none"
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().parent().children().children().children()[1].style.display = "block"
        target.css("-webkit-transform", "rotate(45deg)");
      }
    })
// menu show hide
    $('#primary-menu .navbar-nav>li.mega-dropdown').hover(function () {
      console.log($(this).find('.mega-dropdown').length)
      if ($(this).find('.mega-dropdown').length > 0) {
        $('.wc-page-modal').addClass('show');
      }
    }, function () {
      $('.wc-page-modal').removeClass('show');
    });
//faq system step
//   $('#faq-auth-system').css("background-color","#333")
    $(".view-wristcheck-contact-us .views-field-title .field-content i").click(function () {
      var target = $(this);
      // console.log(target.parent().parent().parent().parent().parent().children()[1].style.display="block")
      if (target.parent().parent().parent().parent().parent().children()[1].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "none"
        // target.parent().parent().parent().parent().parent().children()[1].css("margin-bottom","30px")
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "block"
        target.css("-webkit-transform", "rotate(45deg)");
      }
    })

    var range = document.getElementById('wc-range');

    noUiSlider.create(range, {
      range: {
        'min': 0,
        'max': 1500000
      },
      // Handles start at ...
      start: [0, 1500000],
      tooltip: true,
      connect: true,
      pips: {
        mode: 'positions',
        values: [0, 20000, 500000, 200000, 1500000],
        density: 4
      }
    });

  })
  // category page all brands
  // $('.wc-all-brands-header-list a').on('click', function(){
  //   console.log(this);
  //   $(this).parent().addClass('is-active').siblings().removeClass('is-active')
  // })


})(jQuery, Drupal);
