/**
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal) {

  'use strict';

  $(function () {
    var swiper = new window.Swiper('.swiper-container', {
      loop: true,
      speed: 2500,
      autoplay: true,
      slidesPerView: 5,
      spaceBetween: 30,
      centeredSlides: true,
      watchSlidesProgress: true,
      on: {
        setTranslate: function () {
          var slides = this.slides
          for (var i = 0; i < slides.length; i++) {
            var slide = slides.eq(i);
            var progress = slides[i].progress;
            //slide.html(progress.toFixed(2)); 看清楚progress是怎么变化的
            slide.css({'opacity': '', 'background': ''});
            slide.transform('');//清除样式
            slide.css('opacity', (1 - Math.abs(progress) / 6));
          }
        },
        setTransition: function (transition) {
          for (var i = 0; i < this.slides.length; i++) {
            var slide = this.slides.eq(i);
            slide.transition(transition);
          }
        }
      }
    });
  });
})(jQuery, Drupal);
