console.log('js loaded success');

var $ = jQuery;
//faq dropdown
  $(".field--name-field-faq-question i").click(function(){
    target = $(this);
    target.parent().parent().next().toggle();
    if(target.parent().parent().next()[0].style.display == "block"){
      target.css("-webkit-transform","rotate(45deg)");
    }else{
      target.css("-webkit-transform","rotate(-45deg)");
    }
});
