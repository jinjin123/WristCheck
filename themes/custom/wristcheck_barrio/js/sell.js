(function ($, Drupal) {
  $(function () {
    if (typeof Swiper != 'undefined') {
      var mySwiper = new Swiper('.swiper-container', {
        paginationClickable: true,
        loop: true,
        speed: 2000,
        effect: 'coverflow',
        spaceBetween: 30,
        autoplay: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },
        on: {
          transitionEnd: function () {
            var slide = $('.swiper-container').find('.swiper-slide-active');
            var star = slide.find('.wc-testimdnials-star').html();
            var title = slide.find('.wc-swiper-slide-cont h1').text();
            var local = slide.find('.wc-testimdnials-detail__sub').text();
            $('.wc-swiper-title__top').text(title);
            $('.wc-swiper-title__star').html(star);
            $('.wc-swiper-title__bottom').text(local);
          }
        }
      });
    }
  });
})(jQuery, Drupal);
