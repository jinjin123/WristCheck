/*global  jQuery */
/* Contents
// ------------------------------------------------>
	1.  LOADING SCREEN
	2.  MODULE Click
	3.  SEARCH CLICK
	4.  SIDE NAV CLICK
	5.  MOBILE NAV 
	6.  BACKGROUND
	7.  COUNTDOWN DATE
	8.  COUNTER UP
	9.  SLIDER RANGE
	10. PORTFOLIO FLITER 
	11. MAGNIFIC POPUP
	12. OWL CAROUSEL
	13. AJAX MAILCHIMP
	14. AJAX CAMPAIGN MONITOR
	15. GOOGLE MAP 
	16. WOW
*/
jQuery(document).ready(function() {
    "use strict";

    /* ------------------  LOADING SCREEN ------------------ */

    jQuery(window).on("load", function() {
        jQuery(".preloader").fadeOut("5000");
        jQuery(".preloader").remove();
    });

    /* ------------------  MODULE Click  ------------------ */

    var jQuerymoduleIcon = jQuery(".module-icon"),
        jQuerymoduleCancel = jQuery(".module-cancel");
    jQuerymoduleIcon.on("click", function(e) {
        jQuery(this).parent().siblings().removeClass('module-active'); // Remove the class .active form any sibiling.
        jQuery(this).parent(".module").toggleClass("module-active"); //Add the class .active to parent .module for this element.
        e.stopPropagation();
    });
    // If Click on [ Search-cancel ] Link
    jQuerymoduleCancel.on("click", function(e) {
        jQuery(".module").removeClass("module-active");
        e.stopPropagation();
        e.preventDefault();
    });

    /* ------------------  SEARCH CLICK  ------------------ */

    jQuery(".search-icon").on("click", function() {
        if (jQuery(this).parent().hasClass('module-active')) {
            jQuery(".wrapper").addClass("search-active");
            jQuery(".wrapper").removeClass("hamburger-active");
            jQuery(".module-search-box").addClass("search-box-active");
        }
    });
    jQuery(".search-close").on("click", function() {
        jQuery(".wrapper").removeClass("search-active");
    });

    /* ------------------  SIDE NAV CLICK  ------------------ */

    jQuery(".side-nav-icon").on("click", function() {
        if (jQuery(this).parent().hasClass('module-active')) {
            jQuery(".wrapper").addClass("hamburger-active");
            jQuery(".wrapper").removeClass("search-active");
            jQuery(".hamburger-panel").addClass("hamburger-panel-active");
            jQuery(this).addClass("module-hamburger-close");
        } else {
            jQuery(".wrapper").removeClass("hamburger-active");
            jQuery(".hamburger-panel").removeClass("hamburger-panel-active");
            jQuery(this).removeClass("module-hamburger-close");
        }
    });

    jQuery(document).on('click', function(event) {
        if (!(jQuery(event.target).closest("hamburger-panel-active").length)) {
            // Hide .target-div
            jQuery(".wrapper").removeClass("hamburger-active");
            jQuery('.hamburger-panel').removeClass("hamburger-panel-active");
        }
    });

    jQuery(document).on('click', function(event) {
        if (!(jQuery(event.target).closest(".module-active").length)) {
            // Hide .target-div
            jQuery('.module').removeClass("module-active");
        }
    });

    /* ------------------  MOBILE NAV ------------------ */

    var jQuerydropToggle = jQuery("[data-toggle=dropdown]"),
        jQuerymodule = jQuery(".module");
    jQuerydropToggle.on("click", function(event) {
        event.preventDefault();
        event.stopPropagation();
        jQuery(this).parent().siblings().removeClass("show");
        jQuery(this).parent().toggleClass("show");
    });
    jQuerymodule.on('click', function() {
        jQuery(this).toggleClass("toggle-module");
    });
    jQuerymodule.find("input.form-control", ".btn", ".cancel", ".search-form .form-control").on('click', function(e) {
        e.stopPropagation();
    });

    /* ------------------  BACKGROUND ------------------ */

    var jQuerybgSection = jQuery(".bg-section");
    var jQuerybgPattern = jQuery(".bg-pattern");
    var jQuerycolBg = jQuery(".col-bg");

    jQuerybgSection.each(function() {
        var bgSrc = jQuery(this).children("img").attr("src");
        var bgUrl = 'url(' + bgSrc + ')';
        jQuery(this).parent().css("backgroundImage", bgUrl);
        jQuery(this).parent().addClass("bg-section");
        jQuery(this).remove();
    });

    jQuerybgPattern.each(function() {
        var bgSrc = jQuery(this).children("img").attr("src");
        var bgUrl = 'url(' + bgSrc + ')';
        jQuery(this).parent().css("backgroundImage", bgUrl);
        jQuery(this).parent().addClass("bg-pattern");
        jQuery(this).remove();
    });

    jQuerycolBg.each(function() {
        var bgSrc = jQuery(this).children("img").attr("src");
        var bgUrl = 'url(' + bgSrc + ')';
        jQuery(this).parent().css("backgroundImage", bgUrl);
        jQuery(this).parent().addClass("col-bg");
        jQuery(this).remove();
    });

    /* ------------------ COUNTDOWN DATE ------------------ */
   
    var cms_day = jQuery('#cms_day').attr('value');
    var cms_month = jQuery('#cms_month').attr('value');
    var cms_year = jQuery('#cms_year').attr('value');
    
    var newDate = new Date(cms_year, cms_month, cms_day),
        jQuerycountDown = jQuery("#countdown");
    jQuerycountDown.countdown({
        until: newDate,
        layout: '<div class="timer"><div class="timer-content"><span>{sn}</span><div>second left</div></div></div><div class="timer"><div class="timer-content"><span> {mn}</span><div>Minutes Left</div></div></div><div class="timer"><div class="timer-content"><span>{hn}</span><div>Hours Left</div></div></div><div class="timer"><div class="timer-content"><span> {dn}</span><div>Days Left</div></div></div>'
    });

    /* ------------------  COUNTER UP ------------------ */

    var counter = jQuery(".count-num");
    counter.counterUp({
        delay: 10,
        time: 1000
    });

    /* ------------------ SLIDER RANGE ------------------ */

    var jQuerysliderRange = jQuery("#slider-range"),
        jQuerysliderAmount = jQuery("#amount");
    jQuerysliderRange.slider({
        range: true,
        min: 0,
        max: 500,
        values: [50, 300],
        slide: function(event, ui) {
            jQuerysliderAmount.val("jQuery" + ui.values[0] + " - jQuery" + ui.values[1]);
        }
    });
    jQuerysliderAmount.val("jQuery" + jQuerysliderRange.slider("values", 0) + " - jQuery" + jQuerysliderRange.slider("values", 1));

    /* ------------------ PORTFOLIO FLITER ------------------ */

    var jQueryportfolioFilter = jQuery(".portfolio-filter"),
        portfolioLength = jQueryportfolioFilter.length,
        protfolioFinder = jQueryportfolioFilter.find("a"),
        jQueryportfolioAll = jQuery("#portfolio-all");

    // init Isotope For Portfolio
    protfolioFinder.on("click", function(e) {
        e.preventDefault();
        jQueryportfolioFilter.find("a.active-filter").removeClass("active-filter");
        jQuery(this).addClass("active-filter");
    });
    if (portfolioLength > 0) {
        jQueryportfolioAll.imagesLoaded().progress(function() {
            jQueryportfolioAll.isotope({
                filter: "*",
                animationOptions: {
                    duration: 750,
                    itemSelector: ". portfolio-item ",
                    easing: "linear",
                    queue: false,
                }
            });
        });
    }
    protfolioFinder.on("click", function(e) {
        e.preventDefault();
        var jQueryselector = jQuery(this).attr("data-filter");
        jQueryportfolioAll.imagesLoaded().progress(function() {
            jQueryportfolioAll.isotope({
                filter: jQueryselector,
                animationOptions: {
                    duration: 750,
                    itemSelector: ". portfolio-item ",
                    easing: "linear",
                    queue: false,
                }
            });
            return false;
        });
    });

    /* ------------------ MAGNIFIC POPUP ------------------ */

    var jQueryimgPopup = jQuery(".img-popup");
    jQueryimgPopup.magnificPopup({
        type: "image"
    });

    /* ------------------  SCROLL TO ------------------ */

    var aScroll = jQuery('a[data-scroll="scrollTo"]');
    aScroll.on('click', function(event) {
        var target = jQuery(jQuery(this).attr('href'));
        if (target.length) {
            event.preventDefault();
            jQuery('html, body').animate({
                scrollTop: target.offset().top
            }, 1000);
        }
    });

    /* ------------------ OWL CAROUSEL ------------------ */

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

    /* ------------------  AJAX MAILCHIMP ------------------ */

    jQuery('.mailchimp').ajaxChimp({
        url: "http://wplly.us5.list-manage.com/subscribe/post?u=91b69df995c1c90e1de2f6497&id=aa0f2ab5fa", //Replace with your own mailchimp Campaigns URL.
        callback: chimpCallback

    });

    function chimpCallback(resp) {
        if (resp.result === 'success') {
            jQuery('.subscribe-alert').html('<div class="alert alert-success">' + resp.msg + '</div>').fadeIn(1000);
            //jQuery('.subscribe-alert').delay(6000).fadeOut();

        } else if (resp.result === 'error') {
            jQuery('.subscribe-alert').html('<div class="alert alert-danger">' + resp.msg + '</div>').fadeIn(1000);
        }
    }

    /* ------------------  AJAX CAMPAIGN MONITOR  ------------------ */

    jQuery('#campaignmonitor').submit(function(e) {
        e.preventDefault();
        jQuery.getJSON(
            this.action + "?callback=?",
            jQuery(this).serialize(),
            function(data) {
                if (data.Status === 400) {
                    alert("Error: " + data.Message);
                } else { // 200
                    alert("Success: " + data.Message);
            }
        });
    });

    /* ------------------ GOOGLE MAP ------------------ */

    // jQuery(".googleMap").each(function() {
    //     var jQuerygmap = jQuery(this);
    //     jQuerygmap.gMap({
    //         address: jQuerygmap.data('map-address'),
    //         zoom: jQuerygmap.data('map-zoom'),
    //         maptype: jQuerygmap.data('map-type'),
    //         markers: [{
    //             address: jQuerygmap.data('map-address'),
    //             maptype: jQuerygmap.data('map-type'),
    //             html: jQuerygmap.data('map-info'),
    //             icon: {
    //                 image: jQuerygmap.data('map-maker-icon'),
    //                 iconsize: [76, 61],
    //                 iconanchor: [76, 61]
    //             }
    //         }]
    //     });
    // });

    // /* ------------------ WOW ------------------ */

    // new WOW().init();

}(jQuery));