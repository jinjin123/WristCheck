<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DiscoverController.
 */
class DiscoverController extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return [
      '#theme' => 'wristcheck_discover',
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: index')
    ];
  }

}
