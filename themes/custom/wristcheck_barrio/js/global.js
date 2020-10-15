/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {

	'use strict';

	// debug
	var local = window.location.origin;
	if (local.indexOf('localhost') > -1) {
		$('img').each(function () {
			var img = $(this)
			var url = img.attr('src')
			if (url.match(/^[http|htts]/ig) == null) {
				$(this).attr('src', 'http://dev.wristcheck.com:8888' + url)
			}
		})
		$('.lazyload[data-original]').each(function () {
			var img = $(this)
			var url = img.attr('data-original')
			if (url.match(/^[http|htts]/ig) == null) {
				$(this).attr('data-original', 'http://dev.wristcheck.com:8888' + url)
			}
		})
	}

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
	var loading = function (href) {
		$('body').append($('<div class="ajax-progress wc-progress"><div class="preloader"> <div class="spinner"> <div class="double-bounce1"></div> <div class="double-bounce2"></div> </div> </div></div>'))
		if (href) {
			window.location.href = href;
		} else {
			setTimeout(function () {
				$('.ajax-progress').remove()
			}, 3000)
		}
	}

	// 菜单处理，子菜单超过10个的，添加更多按钮
	$('#block-wristcheck-barrio-main-menu .navbar-nav>li').each(function () {
		var menu = $(this);
		menu.find('.wc-menu-container>li').each(function () {
			var sub_menu = $(this);

			if (sub_menu.find('.navbar-submenus>li').length > 10) {
				let more_link = $('<li><a class="wc-btn-white wc-btn-large wc-menu-read-more" href="javascript:;"><span class="wc-btn-cont">read more</span></a></li>')
				more_link.click(function () {
					loading(sub_menu.children('a').attr('href'))
				})
				sub_menu.find('.navbar-submenus>li').each(function(){
					var el = $(this);
					if(el.index() > 9){
						el.hide();
					}
				})
				sub_menu.find('.navbar-submenus').append(more_link)
			}
		})
	})
	// 监听页面跳转  ajax load 伴随着刷新页面bug事件
	$('a[href]').click(function (e) {
		var href = $(this).attr('href');
		if (href.indexOf('?') == 0) {
			console.log(e);
		} else if (href !== '' &&
			href !== '#' &&
			href !== 'javascript:;' &&
			href !== null &&
			!$(this).hasClass('use-ajax') &&
			!$(this).hasClass('trigger') &&
			!$(this).parent().hasClass('wc-all-brands-header-list__href')) {
			e.stopPropagation()
			loading(href)
		}
	});


	var getALLQuery = function () {
		var allQueryString = location.href.split('?').length > 1 ?
			decodeURI(location.href.split('?')[1]) : '';
		var list = [];
		allQueryString.split('&').map(function (item, index) {
			var info = item.split('=');
			var keys = ['currency_code', 'price[min]', 'price[max]', 'location[]', 'field_related_brand_target_id', 'field_related_model_target_id', 'field_case_diameter_value', 'field_year_value']
			if (keys.indexOf(info[0]) > -1) {
				list.push({ label: info[0], value: info[1] });
			}
		});
		return list;
	};

	var tagsList = $('.wc-search-status-tags');
	if (tagsList.length > 0) {
		var queryList = getALLQuery();
		var htmlBuff = '';
		queryList.map(function (item) {
			if (item.value !== undefined && item.value !== '') {
				var value = item.value;
				if (item.label.indexOf('_id') > -1) {
					value = $('[name="' + item.label + '"][value="' + item.value + '"]').attr("title")
				}
				htmlBuff += '<span class="tag-item" name="' + item.label + '" value="' + item.value + '">' +
					value + ' <a class="remove-tag">x</a></span>';
			}
		});
		tagsList.html(htmlBuff);
		tagsList.on('click', '.remove-tag', function () {
			var _this = $(this);
			var _name = _this.parent().attr('name');
			var _value = _this.parent().attr('value');
			var _index = null;

			queryList.map(function (opt, index) {
				if (opt.label == _name && opt.value == _value) {
					_index = index;
				}
			})
			if (_index != null) {

				queryList.splice(_index, 1);
				_this.parent().remove();
				var redirct = '/product/search-result';
				var queryString = queryList.map(function (search) {
					return search.label + '=' + search.value;
				});
				location.href = redirct + '?' + queryString.join('&');
			}
		});
	}


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
		if (window.location.pathname === "/faq-sellstep") {
			var _ov = document.getElementById('edit-first-unit');
			_ov.removeChild(_ov.options[0]);
			var _sv = document.getElementById('edit-second-unit');
			_sv.removeChild(_sv.options[0]);
		}
		// handle second hand price to update cart form
		// $("#swt").css("display","none");
		// $("#swt").css("color","transparent");
		// console.log($("#wc-product-buy-new >a>div>div")[1].textContent.slice(1))

		var productPrice = $("#wc-product-buy-new .field__item, .price__number, .field--name-price .field__item, td.views-field-unit-price__number, .order-total-line__subtotal .order-total-line-value, .order-total-line__total .order-total-line-value, td.views-field-total-price__number, label.custom-control-label, .checkout-order-summary td,.checkout-order-summary span");
		if (productPrice.text().length > 0) {
			productPrice.map(function (el) {
				if (($(this).text() || "").indexOf('$') > -1) {
					$(this).text($(this).text().replace(/US\$/ig, ' $ '));
				}
			});
		}
		// md-slider change opacity
		$(".md-slide-item").hover(function() {
      $(".md-object").css("opacity","0.2")
      $(".md-object").css("transition","opacity .6s ease .3s;")
    },function (){
      $(".md-object").css("opacity","1")
      }
    )

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
					messages.add("Save your profile successful!", { "type": "status" });
					$('.messages').css("color", "green")
					$('.messages').css("border", "1px solid ")
					$('.messages').css("font-size", "20px")
				} else {
					messages.add("Save your profile Faild! Please try again!", { "type": "error" });
					$('.messages').css("border", "1px solid #red")
					$('.messages').css("font-size", "20px")
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
					mode: 'values',
					values: [0, 300000, 600000, 900000, 1200000, 1500000],
					density: 4
				}
			}).on('change', function (values, handle) {
				if (handle === 0) {
					$('input[name="price[min]"]').val(values[handle])
				} else if (handle === 1) {
					$('input[name="price[max]"]').val(values[handle])
				}
			});
		}
	});
	$('.wc-product-search .wc-search-btn').click(function () {
		var searhBox = $('.wc-product-search');
		var redirct = '/product/search-result';
		var paramsStrArr = [];
		searhBox.find('form').each(function (index, item) {
			var queryString = $(item).serialize();
			var queryArr = $(item).serializeArray();
			if (queryString.length > 0) {
				paramsStrArr.push(queryString)
			}
			// queryArr.map(function (option, index) {
			//   if (option.name && option.name.indexOf('_id') > -1) {
			//     var title = $('[name="' + option.name + '"][value="' + option.value + '"]').attr('title');
			//     localStorage.setItem(option.name + '_' + option.value, title)
			//   }
			// })
		});

		location.href = redirct + (paramsStrArr.length === 0 ? '' : ('?' + paramsStrArr.join('&')))
	});
	$('.wc-product-search .wc-clear-font').click(function () {
		var _this = $(this);
		console.log(_this)
		var _form = _this.parents('form.wc-search-panel__body');
		_form.find('input:checked').each(function (index, item) {
			item.checked = false;
		})

	});
	// category page all brands
	// $('.wc-all-brands-header-list a').on('click', function(){
	//   console.log(this);
	//   $(this).parent().addClass('is-active').siblings().removeClass('is-active')
	// })
  // disable product search result
  $('.layout-container').on('click',function(event){
    if(event.target.className.indexOf("wc-product-search-modal") !=-1){
      var _self = $('.wc-search-menu>ul>li');
      var _wTop = $(window).scrollTop();
      var _box = $('.wc-product-search');
      var _modal = $('.wc-product-search-modal');
      var _menu = _self.parents('.wc-product-search-menu');
      if (_self.hasClass('active')) {
        _self.removeClass('active');
        _modal.removeClass('show');
        _menu.removeClass('active');
        _menu.css({ 'z-index': 2 });

        if (_wTop <= _box.offset().top) {
          _menu.removeClass(('fixed-top')).css({
            top: 0
          })
        }
      }
    }
  })

	// product search bar event
	var is_click = false;
	$('.wc-search-menu>ul>li').on('click', function () {
		var _self = $(this);
		var _wTop = $(window).scrollTop();
		var _box = $('.wc-product-search');
		var _modal = $('.wc-product-search-modal');
		var _menu = _self.parents('.wc-product-search-menu');
		var _admin_toolbar = $('#toolbar-bar .toolbar-tab').height() || 0;
		var _admin_subToobar = $('#toolbar-item-administration-tray.toolbar-tray-horizontal').height() || 0;
		var _top = _admin_toolbar + _admin_subToobar;
		is_click = true;
		setTimeout(function(){
			is_click = false;
		},600)

		if (_self.hasClass('active')) {
			_self.removeClass('active');
			_modal.removeClass('show');
			_menu.removeClass('active');
			_menu.css({ 'z-index': 2 });


			if (_wTop <= _box.offset().top) {
				_menu.removeClass(('fixed-top')).css({
					top: 0
				})
			}
		} else {
			$('html, body').animate({ scrollTop: _box.offset().top }, 300, 'linear', function () {
				_menu.addClass(('fixed-top')).css({
					top: _top + 'px'
				});
			});
			_modal.addClass('show');
			_menu.css({ 'z-index': 5 });
			_menu.addClass('active');
			_self.addClass('active').siblings('.active').removeClass(('active'))
		}
	});

	$('.wc-search-dropdown-panel').on('click', function (e) {
		e.stopPropagation()
	});
	$('.wc-product-search .wc-search-menu-cancel').click('click', function (e) {
		e.stopPropagation();
		var nav = $('.wc-search-menu>ul>li.active');
		var _modal = $('.wc-product-search-modal');
		var _menu = nav.parents('.wc-product-search-menu');
		nav.removeClass('active');
		_modal.removeClass('show');
		_menu.css({ 'z-index': 2 });
	});

	$(window).scroll(function () {
		if(is_click) return false;
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
			_menu.removeClass(('fixed-top')).css({ top: 0 });
		}
	})

	$('.header-box button.navbar-toggler').click(function () {
		var menu = $('#navbarContent');
		var header = $('#navbar-spy');
		if (!menu.hasClass('show')) {
			menu.addClass('show');
			header.addClass('show')
		} else {
			menu.removeClass('show');
			header.removeClass('show')
		}
	})
	$('.header-box .navbar-nav>li').click(function () {
		var _this = $(this);
		if (!_this.hasClass('show')) {
			_this.addClass('show')
			_this.siblings('.show').removeClass('show')
		} else {
			_this.parent().children('.show').removeClass('show')
		}
	})
	$('.menu-close').click(function () {
		var menu = $('#navbarContent');
		var header = $('#navbar-spy');
		var modal = $('.wc-page-modal');
		var search = $('.wc-product-search-modal');
		menu.removeClass('show')
		header.removeClass('show')
		header.find('.show').removeClass('show')
		modal.removeClass('show');
		search.removeClass('show')

	})

})(jQuery, Drupal);
