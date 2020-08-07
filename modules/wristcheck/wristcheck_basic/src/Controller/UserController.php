<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class UserController.
 */
class UserController extends ControllerBase
{

  /**
   * Dashboard.
   *
   * @return array
   *   Return Hello string.
   */
  public function dashboard()
  {
//    dd(views_get_view_result('wristcheck_user_profile','settings'));
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_dashboard',
      '#variables' => $variables
    ];
  }

}
