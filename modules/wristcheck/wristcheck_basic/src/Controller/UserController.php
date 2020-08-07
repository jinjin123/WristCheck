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
  public function info()
  {
//    dd(views_get_view_result('wristcheck_user_profile','settings'));
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_info',
      '#variables' => $variables
    ];
  }

  public function buy()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_buy',
      '#variables' => $variables
    ];
  }

  public function sell()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_sell',
      '#variables' => $variables
    ];

  }

  public function portfolio(){
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_portfolio',
      '#variables' => $variables
    ];
  }

}
