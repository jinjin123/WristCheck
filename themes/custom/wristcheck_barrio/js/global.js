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
    //faq dropdown
    $(".path-wristcheck-faq .view-content .views-row .views-field-title .field-content i").click(function () {
      var target = $(this);
      if (target.parent().parent().parent().parent().parent().children()[2].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[2].style.display = "none"
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[2].style.display = "block"
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
  })
  // category page all brands
  // $('.wc-all-brands-header-list a').on('click', function(){
  //   console.log(this);
  //   $(this).parent().addClass('is-active').siblings().removeClass('is-active')
  // })
  // open wishlist
  $('.wc-product-add-wishlist').click(function (e) {
    e.stopPropagation();
    var panel = $(this).siblings('.wc-product-variations');
    if (panel.hasClass('show')) {
      panel.removeClass('show')
    } else {
      panel.addClass('show')
    }
  });
  $('.wc-product-variations').click(function (e) {
    e.stopPropagation()
  });
  $(document).click(function () {
    $('.wc-product-variations').removeClass('show')
  });
})(jQuery, Drupal);
