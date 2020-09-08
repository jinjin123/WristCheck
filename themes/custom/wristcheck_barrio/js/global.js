/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {

  'use strict';

  // Drupal.behaviors.ProductVariationLoad = {
  //   attach: function (context, settings) {
  //     var display = $(".wc-product-variations");
  //     var url = window.location.pathname.split('/'); console.log();
  //     var product_id = url[url.length-1];
  //
  //     if (url[url.length-2] == 'product' && product_id) {
  //       $('#wc-product-buy-new').off('click').on("click", function() {
  //         var self = $(this);
  //         $.get(Drupal.url("product_variation_form/buy_new/" + product_id), function(data) {
  //           if (data.status == 1) {
  //             $("#wc-product-buy-used").removeClass('active');
  //             self.addClass('active');
  //             display.html(data.data);
  //           } else {
  //             alert(data.message);
  //           }
  //         });
  //       });
  //
  //       $("#wc-product-buy-used").off('click').on("click", function() {
  //         var self = $(this);
  //         $.get(Drupal.url("product_variation_form/buy_used/" + product_id), function(data) {
  //           if (data.status == 1) {
  //             $("#wc-product-buy-new").removeClass('active');
  //             self.addClass('active');
  //             display.html(data.data);
  //           } else {
  //             alert(data.message + 'hello world');
  //           }
  //         });
  //       });
  //     }
  //   }
  // };


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
      $(".links>a.register-popup-form").click(function () {
        $(".ui-dialog-title").css("margin", "0 150px");
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
    // handle second hand price to update cart form
    // $("#swt").css("display","none");
    // $("#swt").css("color","transparent");
    // console.log($("#wc-product-buy-new >a>div>div")[1].textContent.slice(1))
    if ((window.location.pathname).split("/").length > 2 && (window.location.pathname).split("/")[1] == "product") {
      $("#wc-product-buy-new >a>div>div")[1].textContent = $("#wc-product-buy-new >a>div>div")[1].textContent.slice(1);
    }
    var flag = true;
    $('#wc-product-buy-used').click(function () {
      if (flag) {
        $("#wc-product-buy-used>a").css("background-color", "grey");
        // var data = {
        //   "model": $("#swt")[0].textContent,
        //   "price": ($('.views-field-field-price-number>span')[0].textContent).trim()
        // }
        // $.post("/second-hand-update", JSON.stringify(data), function (data) {
        //   if (data == "ok") {
        //     // $(".alert-success").css("display","block");
        //     // console.log("update cahrt")
        //   } else {
        //     // console.log("update errorr")
        //   }
        // })
        flag = false;
      } else {
        // var data = {
        //   "model": $("#swt")[0].textContent,
        //   "price": ($('.views-field-field-price-number>span')[0].textContent).trim(),
        //   "tag": "del"
        // }
        // $.post("/second-hand-update", JSON.stringify(data), function (data) {
        //   if (data == "ok") {
        //     // $(".alert-success").css("display","block");
        //     // console.log("update cahrt")
        //   } else {
        //     // console.log("update errorr")
        //   }
        // })
        $("#wc-product-buy-used>a").css("background-color", "#222222");
        flag = true
      }
      // console.log(($('.views-field-field-price-number>span')[0].textContent).trim());
      // console.log($("#swt")[0].textContent)
    })
    //user wishlist flag
    if ((window.location.pathname).split("/").length > 3) {
      if ((window.location.pathname).split("/")[3] == "wishlist") {
        $(".align-items-center")[0].childNodes[1].remove()
        $(".fa-heart-o")[0].className = "fa fa-times"
        $(".use-ajax").click(function () {
          var target = $(this);
          target.parent().parent().parent().parent().remove()
        })
      }
    }
    //usersupplementform
    $("#profile_button").click(function () {
      // console.log($("#webform-submission-user-info-add-form").serialize())
      $.post("/user-profile", $("#webform-submission-user-info-add-form").serialize(), function (data) {
        const messages = new Drupal.Message();
        // messages.add("Save your profile successful!",{"type":"status"});
        if (data == "ok") {
          // $(".alert-success").css("display", "block");
          messages.add("Save your profile successful!",{"type":"status"});
          $('.messages').css("color","green")
          $('.messages').css("border","1px solid ")
          $('.messages').css("font-size","20px")
        }else{
          messages.add("Save your profile Faild! Please try again!",{"type":"error"});
          $('.messages').css("border","1px solid #red")
          $('.messages').css("font-size","20px")
          // $('.messages').css("color","green")
          // $(".alert-danger").css("display", "block");
        }
      })
    })
    //faq currency
    $(document).ready(function () {
      $("#edit-first-size").on('input', function () {
        var src = $("#edit-first-size")[0].value;
        var funit = $('#edit-first-unit option:selected')[0].textContent;
        var sunit = $('#edit-second-unit option:selected')[0].textContent;
        // console.log(funit,sunit)
        // console.log($('#edit-first-unit option:selected')[0].textContent)
        $.getJSON("/currency-price?search=" + funit, (function (data) {
          $("#edit-second-size")[0].value = (src * JSON.parse(data)[sunit]).toFixed(2);
        }))

      });

    })
    //faq index
    $(".view-content").removeClass("row");
    //faq auth system expose filter
    if (window.location.pathname === "/faq-authsystemstep") {
      $(".path-faq-authsystemstep .view-content")[0].style.display = "none";
    }
    if (window.location.pathname === "/faq-authsystem") {
      $(".path-faq-authsystem .view-content")[0].style.display = "none";
    }

    $(".wc-faq__item__body").css("display", "none")
    var faqflag = true;
    $(".wc-faq .row .wc-faq__item").click(function () {
      var target = $(this);
      if (faqflag) {
        target.find('.wc-faq__item__body ').show();
        target.find('.wc-faq__item__titile').css({
          "background": "url(/themes/custom/wristcheck_barrio/images/icons/arrow-up.png)no-repeat ",
          "background-size": "15px auto",
          "background-position": "100% 30%",
        });
        faqflag = false;
      } else {
        target.find('.wc-faq__item__body ').hide();
        target.find('.wc-faq__item__titile').css({
          "background": "url(/themes/custom/wristcheck_barrio/images/icons/arrow-down.png)no-repeat ",
          "background-size": "15px auto",
          "background-position": "100% 30%",
        });
        faqflag = true;
      }
    });
// menu show hide
    $('#primary-menu .navbar-nav>li.mega-dropdown').hover(function () {
      if ($(this).children('.wc-menu-container').length > 0) {
        $('.wc-page-modal').addClass('show');
      }
    }, function () {
      $('.wc-page-modal').removeClass('show');
    }).children('a[href]').click(function () {
      if (/((http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?)/.test(this.href) && location.href != this.href) {
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
