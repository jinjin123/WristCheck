<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class BuyController.
 */
class BuyController extends ControllerBase
{

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index()
  {

    return [
      '#theme' => 'wristcheck_buy',
//      '#type' => 'markup',
//      '#markup' => $this->t('Implement method: index')
    ];
  }

}
