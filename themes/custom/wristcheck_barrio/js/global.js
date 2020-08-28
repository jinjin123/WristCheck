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
      //register title
      $(".links>a.register-popup-form").click(function(){
        $(".ui-dialog-title").css("margin","0 150px");
      })
    }
  };

  Drupal.behaviors.customDatepicker = {
    attach: function (context, settings) {
      jQuery(function () {
        jQuery("#datepicker").datepicker({
          dateFormat: "dd-mm-yy",
          altField: "input[data-drupal-selector=edit-created]",
          altFormat: "yy/mm/dd 23:59:59"
        });
        jQuery("#watch_year").datepicker({
          dateFormat: "yyyy",
          altField: "input[data-drupal-selector=edit-created]",
          altFormat: "yyyy"
        });
      });
    }
  };
  /**
   * all global common function handler
   * @type {{}}
   */
  Drupal.$wc = {};

  $(function () {
    //usersupplementform
    $("#profile_button").click(function(){
      // console.log($("#webform-submission-user-info-add-form").serialize())
      $.post("/user-profile",$("#webform-submission-user-info-add-form").serialize(),function(data){
        if(data == "ok"){
          $("#webform-submission-user-info-add-form")[0].reset();
          $(".alert-success").css("display","block");
        }else{
          $(".alert-danger").css("display","block");
        }
      })
    })
    //faq currency
    $(document).ready(function () {
      $("#edit-first-size").on('input',function (){
          var src = $("#edit-first-size")[0].value;
          var funit = $('#edit-first-unit option:selected')[0].textContent;
          var sunit = $('#edit-second-unit option:selected')[0].textContent;
        // console.log(funit,sunit)
          // console.log($('#edit-first-unit option:selected')[0].textContent)
        $.getJSON("/currency-price?search="+funit,(function (data){
          $("#edit-second-size")[0].value = (src * JSON.parse(data)[sunit]).toFixed(2);
        }))

      });

    })
    //faq index
    $(".view-content").removeClass("row");
    //faq auth system expose filter
    if(window.location.pathname === "/faq-authsystemstep"){
      $(".path-faq-authsystemstep .view-content")[0].style.display="none";
    }
    if(window.location.pathname === "/faq-authsystem"){
      $(".path-faq-authsystem .view-content")[0].style.display="none";
    }

    // console.log($(".path-faq-authsystem .view-content")[0])
    //faq dropdown
    $(".path-faq .view-content .views-row .views-field-title .field-content i").click(function () {
      var target = $(this);
      if (target.parent().parent().parent().parent().parent().children()[1].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "none";
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "block";
        target.css("-webkit-transform", "rotate(45deg)");
      }
    });
    //faq authsystem
    $(".path-faq-authsystemstep .view-content .views-row .views-field-title .field-content i").click(function () {
      var target = $(this);
      // console.log(target.parent().parent().parent().parent().parent().children()[1]);
      if (target.parent().parent().parent().parent().parent().children()[1].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "none";
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "block";
        target.css("-webkit-transform", "rotate(45deg)");
      }
    });

    //faq authsystem
    $(".path-sell .view-content .views-row .views-field-title .field-content i").click(function () {
      var target = $(this);
      // console.log(target.parent().parent().parent().parent().parent().children()[1]);
      if (target.parent().parent().parent().parent().parent().children()[1].style.display == "block") {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "none";
        target.css("-webkit-transform", "rotate(-45deg)");
      } else {
        target.parent().parent().parent().parent().parent().children()[1].style.display = "block";
        target.css("-webkit-transform", "rotate(45deg)");
      }
    })
// menu show hide
    $('#primary-menu .navbar-nav>li.mega-dropdown').hover(function () {
      if ($(this).children('.wc-menu-container').length > 0) {
        $('.wc-page-modal').addClass('show');
      }
    }, function () {
      $('.wc-page-modal').removeClass('show');
    }).children('a[href]').click(function(){
      if(/((http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?)/.test(this.href) && location.href != this.href){
        location.href = this.href;
      }
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
    var _top = _admin_toolbar + _admin_subToobar;

    if (_self.hasClass('active')) {
      _self.removeClass('active');
      _modal.removeClass('show');
      _menu.css({'z-index': 2});


      if (_wTop <= _box.offset().top) {
        _menu.removeClass(('fixed-top')).css({
          top: 0
        })
      }
    } else {
      $('html, body').animate({scrollTop: _box.offset().top}, 300, 'linear', function () {
        _menu.addClass(('fixed-top')).css({
          top: _top + 'px'
        });
      });
      _modal.addClass('show');
      _menu.css({'z-index': 5})
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
    var _top = _admin_toolbar + _admin_subToobar;

    if (_wTop > _box.offset().top) {
      _menu.addClass(('fixed-top')).css({
        top: _top + 'px'
      });
    } else if (_menu.hasClass('fixed-top')) {
      _menu.removeClass(('fixed-top')).css({top: 0});
    }
  })


})(jQuery, Drupal);
