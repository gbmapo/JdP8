(function ($, Drupal) {
  Drupal.behaviors.myThemeBehavior = {
    attach: function (context, settings) {
      $('.use-ajax', context).once('auto-open').each(function () {
        $(this).click();
      });
    }
  };
})(jQuery, Drupal);
