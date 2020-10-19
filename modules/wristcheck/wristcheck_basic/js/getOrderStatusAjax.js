(function ($, Drupal) {
  Drupal.behaviors.WricheckBasicGetOrderStatus = {
    attach: function (context, settings) {
      $("a.transport-link").click(function (e) {
        e.preventDefault();
        let order_id = $(this).data("order-id");
        $.ajax({
          url: "/user/transactions/buy/"+order_id+"/status",
          context: this,
        }).done(function(data) {
          let order_id = $(this).data("order-id");
          let result = data.output;
          $("#buy-order-status-"+order_id).html(result);
        });
      });
    }
  };
})(jQuery, Drupal);

