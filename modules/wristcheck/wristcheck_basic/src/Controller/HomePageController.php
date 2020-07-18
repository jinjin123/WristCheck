<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HomePageController.
 */
class HomePageController extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {


    # Call a block
    # https://www.drupal.org/docs/8/modules/twig-tweak/rendering-blocks-with-twig-tweak
    // $custom_block = drupal_block('wristcheck_blockabout4_3');


    # Call a Views
    // $custom_views = views_embed_view('blog', 'blog_all');

    


    // return [
    //   '#type' => 'markup',
    //   '#markup' => $this->t('Implement method: index')
    // ];

    return [
      '#theme' => 'wristcheck_homepage',
      '#test_var' => $this->t('Test Value123321'),
      // '#test_basic_bolgs' => drupal_render($custom_views),
      // '#test_custom_block' => $custom_block
    ];
  }

}
