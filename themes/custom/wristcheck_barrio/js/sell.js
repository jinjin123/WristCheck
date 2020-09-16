(function ($, Drupal) {
  $(function () {
    if (typeof Swiper != 'undefined') {
      var mySwiper = new Swiper('.swiper-container', {
        paginationClickable: true,
        loop: true,
        effect:'coverflow',
        spaceBetween: 30,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        }
      });
    }
  });
})(jQuery, Drupal);
