<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class FaqController.
 */
class FaqController extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return [
      '#theme' => 'wristcheck_faq',
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: index')
    ];
  }

}
