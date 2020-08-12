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

  public function faqauthsystem() {
    return [
      '#theme' => 'wristcheck_faq_authsystem',
      '#type' => 'markup',
    ];
  }

  public function faqsellstep() {
    return [
      '#theme' => 'wristcheck_faq_sellstep',
      '#type' => 'markup',
    ];
  }

  public function faqauthsystemstep() {
    return [
      '#theme' => 'wristcheck_faq_authsystemstep',
      '#type' => 'markup',
    ];
  }

}
