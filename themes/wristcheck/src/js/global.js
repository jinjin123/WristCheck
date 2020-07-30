console.log('js loaded success');

var $ = jQuery;
//faq dropdown
$(".views-field-title .field-content i").click(function () {
  target = $(this);
  if (target.parent().parent().parent().parent().parent().children()[2].style.display == "block") {
    target.parent().parent().parent().parent().parent().children()[2].style.display = "none"
    target.css("-webkit-transform", "rotate(-45deg)");
  } else {
    target.parent().parent().parent().parent().parent().children()[2].style.display = "block"
    target.css("-webkit-transform", "rotate(45deg)");
  }
});

// menu show hide
$('#primary-menu .navbar-nav>li.mega-dropdown').hover(function () {
  if ($(this).find('.mega-dropdown-menu').length > 0) {
    $('.body-modal').addClass('show');
  }
}, function () {
  $('.body-modal').removeClass('show');
});
  $('.view-faq-slick-img').slick({
    dots: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 5000,
    fade: false,
    cssEase: 'ease',
    arrows: false,
    rows: 1,
    responsive: [{
      breakpoint: 991,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }, {
      breakpoint: 767,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }]
  });
