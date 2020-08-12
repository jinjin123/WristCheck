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
  /**
   * product thumb image load
   * @type {{attach: Drupal.behaviors.loadBgImg.attach}}
   * element: <div class="lazyload" data-original="{{imgURL}}"></div>
   * js: Drupal.behaviors.loadBgImg();
   */
  Drupal.behaviors.loadBgImg = {
    attach: function (context, settings) {
      console.log($('.lazyload[data-original]').length);
      $('.lazyload[data-original]').each(function () {
        var el = $(this);
        var img = el.data('original');
        el.css({
          'background-image': 'url("' + img + '")'
        })
      })
    }
  };
  /**
   * all global common function handler
   * @type {{}}
   */
  Drupal.$wc = {};

  $(function () {
    //todo: dynamic webform price request
    //faq index
    $(".view-content").removeClass("row");
    //faq auth system expose filter
    $(".path-faq-authsystemstep .view-content")[0].style.display="none";
    //faq dropdown
    $(".path-faq .view-content .views-row .views-field-title .field-content i").click(function () {
      var target = $(this);
      if (target.parent().parent().parent().parent().parent().children()[2].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[2].style.display = "none"
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[2].style.display = "block"
        target.css("-webkit-transform", "rotate(45deg)");
      }
    })
    //faq authsystem
    $(".path-faq-authsystemstep .view-content .views-row .views-field-title .field-content i").click(function () {
      var target = $(this);
      // console.log(target.parent().parent().parent().parent().parent().children()[1]);
      if (target.parent().parent().parent().parent().parent().children()[1].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "none"
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "block"
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

    var range = document.getElementById('wc-range');
    if (noUiSlider && range) {
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
    }
  });
  // category page all brands
  // $('.wc-all-brands-header-list a').on('click', function(){
  //   console.log(this);
  //   $(this).parent().addClass('is-active').siblings().removeClass('is-active')
  // })

  // product search bar event
  $('.wc-search-menu>ul>li').on('click', function () {
    var _self = $(this);
    var _wTop = $(window).scrollTop();
    var _box = $('.wc-product-search');
    var _modal = $('.wc-product-search-modal');
    var _menu = _self.parents('.wc-product-search-menu');
    var _admin_toolbar = $('#toolbar-bar .toolbar-tab').height() || 0;
    var _admin_subToobar = $('#toolbar-item-administration-tray.toolbar-tray-horizontal').height() || 0;

    if (_self.hasClass('active')) {
      _self.removeClass('active');
      _modal.hide();

      if (_wTop <= _box.offset().top) {
        _menu.removeClass(('fixed-top')).css({
          top: 0
        })
      }
    } else {
      $('html, body').animate({scrollTop: _box.offset().top}, 300, 'linear', function () {
        _menu.addClass(('fixed-top')).css({
          top: _admin_toolbar + _admin_subToobar + 'px'
        });
      });
      _modal.show();
      _self.addClass('active').siblings('.active').removeClass(('active'))
    }
  });
  $('.wc-search-dropdown-panel').on('click', function (e) {
    e.stopPropagation()
  });
  $(window).scroll(function () {
    var _wTop = $(this).scrollTop();
    var _box = $('.wc-product-search');
    var _menu = $('.wc-product-search .wc-product-search-menu');
    var _admin_toolbar = $('#toolbar-bar .toolbar-tab').height() || 0;
    var _admin_subToobar = $('#toolbar-item-administration-tray.toolbar-tray-horizontal').height() || 0;

    if (_wTop > _box.offset().top) {
      _menu.addClass(('fixed-top')).css({
        top: _admin_toolbar + _admin_subToobar + 'px'
      });
    } else if (_menu.hasClass('fixed-top')) {
      _menu.removeClass(('fixed-top')).css({top: 0});
    }
  })


})(jQuery, Drupal);
