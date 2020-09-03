(function ($, Drupal) {
  $(function () {
    if (Swiper) {
      var mySwiper = new Swiper('.sell-swiper-container', {
        // 如果需要分页器
        pagination: {
          el: '.swiper-pagination',
        },
        // 如果需要前进后退按钮
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        }
      });
    }
  });
})(jQuery, Drupal);
