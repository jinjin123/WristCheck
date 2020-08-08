<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DiscoverController.
 */
class DiscoverController extends ControllerBase
{

  /**
   * Index.
   *
   * @return string[]
   *   Return Hello string.
   */
  public function index()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_discover',
      '#variables' => $variables
    ];
  }

}
