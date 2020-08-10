(function ($) {
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

    // product img slick
    $('.wc-product-imglist1 .view-content').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      nextArrow: '',
      prevArrow: '',
      centerPadding: '0',
      asNavFor: '.wc-product-imglist2 .view-content'
    });
    $('.wc-product-imglist2 .view-content').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      asNavFor: '.wc-product-imglist1 .view-content',
      dots: false,
      arrows: false,
      centerPadding: '0',
      prevArrow: '',
      nextArrow: '',
      centerMode: true,
      focusOnSelect: true
    });
    // product img slick end

  });
})(jQuery);
