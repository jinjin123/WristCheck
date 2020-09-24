<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ErrorPageController.
 */
class ErrorPageController extends ControllerBase
{

  public function error404()
  {
    $variables = [];
    return [
      '#variables' => $variables,
      '#theme' => 'wristcheck_error_404'
    ];
  }

  public function error403()
  {
    $variables = [];
    return [
      '#variables' => $variables,
      '#theme' => 'wristcheck_error_403'
    ];
  }
}
