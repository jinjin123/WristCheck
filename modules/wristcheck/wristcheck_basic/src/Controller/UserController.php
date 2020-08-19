<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\facets\Exception\Exception;
use Drupal\user\UserAuthInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\profile\Entity\Profile;

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

  public function usersupplement()
  {
    $user = \Drupal\user\Entity\User::load('1');
    $variables['mail'] = $user->getEmail();
    if (!$user->user_picture->isEmpty()) {
//      $variables['picture'] = file_create_url($user->user_picture->entity->getFileUri());
      $variables['picture'] = $user->user_picture->view('large');
    }else{
      $variables['picture']  = '';
    }

    return [
      '#theme' => 'wristcheck_user_supplement_form',
      '#variables' => $variables
    ];
  }

  public function useractivate()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_user_useractivate',
      '#variables' => $variables
    ];
  }

  public function  userprofile()
  {
    try{
      $name = \Drupal::request()->request->get('names');
      $last_name = \Drupal::request()->request->get('last_name');
      $sex = \Drupal::request()->request->get('sex_selection');
      $profile = Profile::create([
        'type' => 'customer',
        'uid' => $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id()),
        'address' => [
          'country_code' => '--',
          'address_line1' => '--',
          'locality' => '--',
          'administrative_area' => '--',
          'postal_code' => '--',
          'family_name' => $name,
          'given_name' => $last_name,
        ],
        'field_sex'=>$sex,
      ]);
      $profile->save();

//      \Drupal::logger('User_profile')->error('user profile save faild' . $name.$last_name.$sex);
      return new Response("ok");
    }catch (Exception $e){
      return new Response("faild",403);
    }
  }

}
