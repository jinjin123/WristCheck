(function($) {

	  $(document).ready(function(){

	    $(".mega-dropdown > ul").addClass('mega-dropdown-menu');

	    $(".mega-dropdown > .mega-dropdown-menu").wrapInner(' <li> <div class="container"> <div class="row">');

	    $(".mega-dropdown > .mega-dropdown-menu li.col-md-3  ul").removeClass('dropdown-menu');

	    $(".mega-dropdown > .mega-dropdown-menu li.col  ul").removeClass('dropdown-menu');

  	});

}(jQuery));



(function($){

  $(document).ready(function () {

      var counter = 0;

      var i = 2;

        $(".portfolio-filter ul li+li a").each(function(index, el) {

           var filter =  $(this).data('filter');

           $(filter).each(function(index, el) {

             counter = counter + 1;

           });

          $(".portfolio-filter ul li+li:nth-child("+i+") a span").text(counter);

           // alert(counter);

           i = i + 1;

           counter = 0;

        });

       $(" .portfolio-item .portfolio-img img").each(function(index, el) {

          counter = counter + 1;

          $(".portfolio-filter ul li:nth-child(1) a span").text(counter);

        });

       $(".comment-form-body input[type='text'],.comment-form-body input[type='tel'],.comment-form-body input[type='email'],.comment-form-body textarea").addClass('form-control mb-30');

       $(".sidebar-filter .form-item-price__number-min label:first-child").wrap('<div class="widget-title">');

       $(".sidebar-filter .js-form-item-title label").wrap('<div class="widget-title">');

       $(".sidebar-filter .js-form-item-title input[type='text']").addClass('form-control');

       // $(".comment-form-body input[type='text'],.comment-form-body input[type='tel'],.comment-form-body input[type='email'],.comment-form-body textarea").wrap('<div class="col-md-12">');

       $(".comment-form-body input[type='submit']").addClass('comment-btn');

       $(".sidebar-filter #edit-submit-product-view").addClass('btn');

       $("ul li:first-child .service-active-js").addClass('active show');

       $(".tab-content div:first-child.tab-pane").addClass('active show');

        jQuery(".carousel").each(function() {
            var jQueryCarousel = jQuery(this);
            jQueryCarousel.owlCarousel({
                loop: jQueryCarousel.data('loop'),
                autoplay: jQueryCarousel.data("autoplay"),
                margin: jQueryCarousel.data('space'),
                nav: jQueryCarousel.data('nav'),
                dots: jQueryCarousel.data('dots'),
                center: jQueryCarousel.data('center'),
                dotsSpeed: jQueryCarousel.data('speed'),
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: jQueryCarousel.data('slide-rs'),
                    },
                    1000: {
                        items: jQueryCarousel.data('slide'),
                    }
                }
            });
        });

        jQuery('.owl-carousel').owlCarousel({
            thumbs: true
        });

       

     });

})(jQuery);