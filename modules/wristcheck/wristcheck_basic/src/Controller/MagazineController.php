<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MagazineController.
 */
class MagazineController extends ControllerBase
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
      '#theme' => 'wristcheck_magazine',
      '#variables' => $variables
    ];
  }

  /**
   * Index.
   *
   * @return string[]
   *   Return Hello string.
   */
  public function single()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_single',
      '#variables' => $variables
    ];
  }


}

