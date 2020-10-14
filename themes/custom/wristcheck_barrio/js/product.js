(function ($) {
  var buy_used_from = $('.view-display-id-product_single_page_two_hands_lists');
    if(buy_used_from.children().length === 1){
      $('#wc-product-buy-used').hide();
    }
  $(function () {
    //TODO: this page global event

    // wishlist
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
    // wishlist end
    var p = 500 / 680;
    var thumb_image_width = 680;
    var thumb_image_height = 500;
    var box_width = $('.wc-product-imglist1').innerWidth();

    if (box_width < 680) {
      thumb_image_width = box_width;
      thumb_image_height = box_width * p;
    }

    $('.wc-product-imglist1').css('height', (thumb_image_height + 160) + 'px');


    $(".etalage").zoom({
      align: "left",              // 当前展示图片的位置，则放大的图片在其相对的位置
      thumb_image_width: thumb_image_width,     // 当前展示图片的宽
      thumb_image_height: thumb_image_height,    // 当前展示图片的高
      source_image_width: 2040,    // 放大图片的宽
      source_image_height: 1500,  // 放大图片的高
      zoom_area_width: thumb_image_width - 5,       // 放大图片的展示区域的宽
      zoom_area_height: 700,// 放大图片的展示区域的高
      zoom_area_distance: 10,     //
      zoom_easing: true,          // 是否淡入淡出
      description_opacity: 0.7,
      small_thumbs: 3,            // 小图片展示的数量
      smallthumb_inactive_opacity: 0.4,   // 小图片处于非激活状态时的遮罩透明度
      smallthumbs_position: "bottom",     // 小图片的位置
      show_icon: true,
      hide_cursor: false,         // 鼠标放到图片时，是否隐藏指针
      speed: 600,                 //
      autoplay: true,             // 是否自动播放
      autoplay_interval: 6000,    // 自动播放时每张图片的停留时间
    });

  });
})(jQuery);
