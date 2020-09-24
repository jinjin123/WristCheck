(function ($, window, Drupal, drupalSettings) {
  /**
   * Creates a new Snowman progress indicator, which really is full screen.
   */
  Drupal.Ajax.prototype.setProgressIndicatorWCFullprogress = function () {
    this.progress.element = $('<div class="ajax-progress wc-progress"><div class="preloader"> <div class="spinner"> <div class="double-bounce1"></div> <div class="double-bounce2"></div> </div> </div></div>');
    $('body').append(this.progress.element);
  };

  var originalThrobber = Drupal.Ajax.prototype.setProgressIndicatorThrobber;
  Drupal.Ajax.prototype.setProgressIndicatorThrobber = function () {
    var $target = $(this.element);
    var progress = $target.data('progressType') || 'throbber';
    if (progress === 'throbber') {
      originalThrobber.call(this);
    } else {
      var progressIndicatorMethod = 'setProgressIndicator' + progress;
      if (progressIndicatorMethod in this && typeof this[progressIndicatorMethod] === 'function') {
        this[progressIndicatorMethod].call(this);
      }
    }
  };

})(jQuery, window, Drupal, drupalSettings);
