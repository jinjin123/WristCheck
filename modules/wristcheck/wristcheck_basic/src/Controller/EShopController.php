<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class EShopController.
 */
class EShopController extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return [
      '#theme' => 'wristcheck_eshop',
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: index')
    ];
  }

}
