<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    $user = \Drupal\user\Entity\User::load('1');
    $variables['mail'] = $user->getEmail();
    $variables['phone'] = $user->get('field_phone_number')->value;
    return [
      '#theme' => 'wristcheck_sell',
      '#variables' => $variables,
    ];
  }

  public function sellperson()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_sellperson',
      '#variables' => $variables,
    ];
  }

  public function sellwatchinput()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_sellwatchinput',
      '#variables' => $variables,
    ];
  }

  public function sellsecond()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_sellsecond',
      '#variables' => $variables,
    ];
  }

  public function getRate() {
    $rateSettings = \Drupal::config('wristcheck_basic.rateSettings')->get();
    return new JsonResponse($rateSettings);
  }

}
