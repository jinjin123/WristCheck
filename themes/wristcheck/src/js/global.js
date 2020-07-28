console.log('js loaded success');

var $ = jQuery;
//faq dropdown
  $(".views-field-title .field-content i").click(function(){
    target = $(this);
    if(target.parent().parent().parent().parent().parent().children()[2].style.display=="block"){
      target.parent().parent().parent().parent().parent().children()[2].style.display="none"
      target.css("-webkit-transform","rotate(-45deg)");
    }else{
      target.parent().parent().parent().parent().parent().children()[2].style.display="block"
      target.css("-webkit-transform","rotate(45deg)");
    }
});
