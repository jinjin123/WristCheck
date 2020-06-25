(function ($, drupalSettings) {
  var baseURL = drupalSettings.path.themeUrl;

  var lat = $('.unidesi_map').data('lat');
  var lng = $('.unidesi_map').data('lag');
	
      jQuery(".googleMap").each(function() {
        var jQuerygmap = jQuery(this);
        jQuerygmap.gMap({
            address: jQuerygmap.data('map-address'),
            zoom: jQuerygmap.data('map-zoom'),
            maptype: jQuerygmap.data('map-type'),
            markers: [{
                address: jQuerygmap.data('map-address'),
                maptype: jQuerygmap.data('map-type'),
                html: jQuerygmap.data('map-info'),
                icon: {
                    image: jQuerygmap.data('map-maker-icon'),
                    iconsize: [76, 61],
                    iconanchor: [76, 61]
                }
            }]
        });
    });

    /* ------------------ WOW ------------------ */

    new WOW().init();
})(jQuery, drupalSettings);