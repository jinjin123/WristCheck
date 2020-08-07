<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class SellController.
 */
class SellController extends ControllerBase
{

  /**
   * Index.
   *
   * @return string[]
   *   Return Hello string.
   */
  public function index()
  {
    return [
      '#theme' => 'wristcheck_sell',
    ];
  }

}
