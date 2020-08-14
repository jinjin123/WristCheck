<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\facets\Exception\Exception;
use Drupal\Component\Utility\Html;
use Drupal\user\UserAuthInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class UserController.
 */
class UserController extends ControllerBase
{

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
//  protected $mailManager;
//
//  public function __construct(MailManagerInterface $mail_manager) {
//    $this->mailManager = $mail_manager;
//  }

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


  public function Register(Request $request) {
    try {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $name = \Drupal::request()->request->get('Name');
      $email = \Drupal::request()->request->get('Email');
      $pwd = $this->randompwd();
      // check exits email
      $ids = \Drupal::entityQuery('user')
        ->condition('mail', $email)
        ->execute();

      if (!empty($ids)) {
        return new Response("this mail already exists",403);
      } else {
        // create user
        $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $user = \Drupal\user\Entity\User::create();
        $user->setUsername($name);
        $user->setPassword($pwd);
        $user->setEmail($email);
        $user->set("init",  $email);
        $user->set("langcode", $langcode);
        $user->set("preferred_langcode", $langcode);
        $user->set("preferred_admin_langcode", $langcode);
        $user->activate();
        $user->save();
        //send email
//      $sys_mail  = \Drupal::config('system.site')->get('mail');
//        $params['subject'] = t('Drupal SMTP test e-mail');
        $params['subject'] = t('Wristcheck Username & Password Email');
        $params['body'] = t('You Username is: @user , Your  password is: @pass', ['@user' => $name, '@pass'=> $pwd]);
        $params['headers'] = [
          'content-type' => 'text/plain',
        ];
        $mailManager->mail('wristcheck_basic', 'smtp-test', $email, $langcode, $params,NULL,true);
        return new Response();
      }
    }catch (Exception $e){
      \Drupal::logger('User_Register')->error('user register faild' . json_encode($e));
    }
    return new Response();
  }

  private function randompwd($pw_length=8){
    $randpwd ='';
    for ($i= 0; $i < $pw_length; $i++)
    {
      $randpwd .=chr(mt_rand(33, 126));
    }
    return $randpwd;
  }

}
