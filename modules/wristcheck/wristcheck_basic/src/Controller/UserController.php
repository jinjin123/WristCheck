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

  /**
   * Gets the format of the current request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return string
   *   The format of the request.
   */
  protected function getRequestFormat(Request $request) {
    $format = $request->getRequestFormat();
    if (!in_array($format, $this->serializerFormats)) {
      throw new BadRequestHttpException("Unrecognized format: $format.");
    }
    return $format;
  }

  public function Register(Request $request) {
    try {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $name = \Drupal::request()->request->get('Name');
      $email = \Drupal::request()->request->get('Email');
      $pwd = $this->randompwd();
      // create user
      $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
      $user = \Drupal\user\Entity\User::create();
      $user->setUsername($name);
      $user->setPassword($pwd);
      $user->setEmail($email);
      $user->set("init",  $email);
      $user->set("langcode", $lang);
      $user->set("preferred_langcode", $lang);
      $user->set("preferred_admin_langcode", $lang);
      $user->activate();
      $user->save();
      //send email

//      $sys_mail  = \Drupal::config('system.site')->get('mail');
//      $params['subject'] = t('Drupal SMTP test e-mail');
//      $params['body'] = array(t('If you receive this message it means your site is capable of using SMTP to send e-mail.'));

//      $mailManager->mail('smtp', 'smtp', $email, $lang, $params);
    }catch (Exception $e){
      \Drupal::logger('User_Register')->error('user register' . json_encode($e));
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
