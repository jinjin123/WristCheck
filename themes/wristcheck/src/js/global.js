console.log('js loaded success');

var $ = jQuery;
//faq dropdown
$(".path-wristcheck-faq .views-field-title .field-content i").click(function() {
  target = $(this);
  if (target.parent().parent().parent().parent().parent().children()[2].style.display == "block") {
    target.parent().parent().parent().parent().parent().children()[2].style.display = "none"
    target.css("-webkit-transform", "rotate(-45deg)");
  } else {
    target.parent().parent().parent().parent().parent().children()[2].style.display = "block"
    target.css("-webkit-transform", "rotate(45deg)");
  }
})
// menu show hide
$('#primary-menu .navbar-nav>li.mega-dropdown').hover(function () {
  if ($(this).find('.mega-dropdown-menu').length > 0) {
    $('.body-modal').addClass('show');
  }
}, function () {
  $('.body-modal').removeClass('show');
});
//faq system step
$('#faq-auth-system').css("background-color","#333")
$(".view-wristcheck-contact-us .views-field-title .field-content i").click(function(){
  target = $(this);
  // console.log(target.parent().parent().parent().parent().parent().children()[1].style.display="block")
  if(target.parent().parent().parent().parent().parent().children()[1].style.display=="block"){
    target.parent().parent().parent().parent().parent().children()[1].style.display="none"
    // target.parent().parent().parent().parent().parent().children()[1].css("margin-bottom","30px")
    target.css("-webkit-transform","rotate(-45deg)");
  }else{
    target.parent().parent().parent().parent().parent().children()[1].style.display="block"
    target.css("-webkit-transform","rotate(45deg)");
  }
});
