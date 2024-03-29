<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserLoginForm.
 */
class UserLoginForm extends FormBase
{

  /**
   * Drupal\Core\Flood\FloodInterface definition.
   *
   * @var \Drupal\Core\Flood\FloodInterface
   */
  protected $flood;

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * Drupal\user\UserAuthInterface definition.
   *
   * @var \Drupal\user\UserAuthInterface
   */
  protected $userAuth;

  /**
   * Drupal\Core\Render\RendererInterface definition.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'user_login_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // get user login form.
    $form = \Drupal::formBuilder()->getForm('Drupal\user\Form\UserLoginForm');

    // add register class.
//    $register = Link::fromTextAndUrl($this->t('Register Now?'), Url::fromRoute("wristcheck_basic.user_register_form"))->toRenderable();
//    $register = Link::fromTextAndUrl($this->t('Register Now?'), Url::fromRoute("wristcheck_basic.user_register_form"))->toRenderable();
//    $register['#attributes'] = array(
//      'class' => array(
//        'use-ajax',
//        'register-popup-form'
//        ),
//      'data-dialog-options' => Json::encode([
//        'width' => 730,
//        'dialogClass' => 'wc-register-dialog'
//      ]),
//      'data-dialog-type' => 'modal',
//    );

    // add forget class.
    $forget = Link::fromTextAndUrl($this->t(' Forget your password?'), Url::fromRoute('user.pass'))->toRenderable();
    $forget['#attributes'] = ['class' => 'forget'];

    $form['links'] = [
      '#type' => 'markup',
//      '#markup' => '<div class="links">'. render($register) . render($forget) .'</div>',
      '#markup' => '<div class="links">'. render($forget) .'</div>',
//      '#suffix'=>'<div class="form-footer text-center"><p class="form-footer-title">' . $this->t('Do you not currently have a user account?') . '</p><div><a class="wc-btn-dark"><div class="wc-btn-cont"><span class="fa fa-arrow-right"></span> <span class="btn-line"></span> <span>' . $this->t('IN THE CONTINUE') . '</span></div></a></div></div>',
      '#weight' => 1000,
    ];
    return $form;
//    $config = $this->config('system.site');
//    $form['message'] = [
//      '#type' => 'markup',
//      '#markup' => '<div class="result_message"></div>'
//    ];
//
//    // Display login form:
//    $form['name'] = [
//      '#type' => 'textfield',
//      '#title' => $this->t('Username'),
//      '#size' => 60,
//      '#maxlength' => USERNAME_MAX_LENGTH,
//      '#description' => $this->t('Enter your @s username.', ['@s' => $config->get('name')]),
//      '#required' => TRUE,
//      '#attributes' => [
//        'autocorrect' => 'none',
//        'autocapitalize' => 'none',
//        'spellcheck' => 'false',
//        'autofocus' => 'autofocus',
//        'placeholder' => $this->t('Username'),
//      ],
//    ];
//
//    $form['pass'] = [
//      '#type' => 'password',
//      '#title' => $this->t('Password'),
//      '#size' => 60,
//      '#description' => $this->t('Enter the password that accompanies your username.'),
//      '#required' => TRUE,
//      '#attributes' => [
//        'placeholder' => $this->t('Password'),
//      ],
//    ];
//
//    $form['actions'] = [
//      '#type' => 'button',
//      '#value' => $this->t('Log in'),
//      '#ajax' => array(
//        'callback' => array($this, 'validateAjax'),
//        'event' => 'click',
//        'progress' => array(
//          'type' => 'throbber',
//          'message' => t('Verifying...'),
//        ),
//      ),
//
//    ];
//    $form['links'] = [
//      '#type' => 'markup',
//      '#markup' => '<div class="links"><a class="register" href="' . $base_url . '/user/register">' . $this->t('No user account yet?') . '</a><a class="forget" href="' . $base_url . '/user/password">' . $this->t('Forget Password?') . '</a></div>',
//      '#suffix'=>'<div class="form-footer text-center"><p class="form-footer-title">Do you not currently have a user account?</p><div><a class="wc-btn-dark"><div class="wc-btn-cont"><span class="fa fa-arrow-right"></span> <span class="btn-line"></span> <span>IN THE CONTINUE</span></div></a></div></div>'
//    ];
//
//    $this->renderer->addCacheableDependency($form, $config);
//    return $form;
  }


//  /**
//   * {@inheritdoc}
//   */
//  public function validateForm(array &$form, FormStateInterface $form_state)
//  {
//    foreach ($form_state->getValues() as $key => $value) {
//      // @TODO: Validate fields.
//    }
//    parent::validateForm($form, $form_state);
//  }


  /**
   * Ajax callback to validate the email field.
   * @param array $for$floodm
   * @param FormStateInterface $form_state
   * @param $account
   * @return AjaxResponse
   */
  public function validateAjax(array &$form, FormStateInterface $form_state, $account)
  {
//    $error = '';
//    $link_name = \Drupal::config('redirection_config_form.settings')->get('link');
//    // validate fields
//    if ($form_state->isValueEmpty('name') || $form_state->isValueEmpty('pass')) {
//      // Blocked in user administration.
//      $error = $this->t('The username and password is required')->render();
//      $response = new AjaxResponse();
//      $response->addCommand(
//        new HtmlCommand(
//          '.result_message',
//          '<div class="my_top_message">' . $error . '</div>')
//      );
//
//      return $response;
//    } // validateName
//    elseif (user_is_blocked($form_state->getValue('name'))) {
//      // Blocked in user administration.
//      $error = $this->t('The username %name has not been activated or is blocked.', ['%name' => $form_state->getValue('name')])->render();
//      $response = new AjaxResponse();
//      $response->addCommand(
//        new HtmlCommand(
//          '.result_message',
//          '<div class="my_top_message">' . $error . '</div>')
//      );
//
//      return $response;
//    } //validateFinal
//    elseif ($error == '') {
//      //validateAuthentication
//      $this->validateAuthentication($form, $form_state);
//      $error = $this->validateFinal($form, $form_state);
//      if (empty($error)) {
//        // Login success
//        $this->submitForm($form, $form_state);
//        $response = new AjaxResponse();
//        global $base_url;
//        $response->addCommand(new RedirectCommand($base_url . '/' . $link_name));
//      } else {
//        $msg = implode('<br>', $error);
//        $response = new AjaxResponse();
//        $response->addCommand(
//          new HtmlCommand(
//            '.result_message',
//            '<div class="my_top_message">' . $msg . '</div>')
//        );
//      }
//      return $response;
//    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
//    $account = $this->userStorage->load($form_state->get('uid'));
//    // A destination was set, probably on an exception controller,
//    if (!$this->getRequest()->request->has('destination')) {
//      $form_state->setRedirect(
//        'entity.user.canonical',
//        ['user' => $account->id()]
//      );
//    } else {
//      $this->getRequest()->query->set('destination', $this->getRequest()->request->get('destination'));
//    }
//
//    user_login_finalize($account);
  }


  /**
   * Checks supplied username/password against local users table.
   *
   * If successful, $form_state->get('uid') is set to the matching user ID.
   */
  public function validateAuthentication(array &$form, FormStateInterface $form_state)
  {
//    $password = trim($form_state->getValue('pass'));
//    $flood_config = $this->config('user.flood');
//    if (strlen($password) > 0) {
//      // Do not allow any login from the current user's IP if the limit has been
//      // reached. Default is 50 failed attempts allowed in one hour. This is
//      // independent of the per-user limit to catch attempts from one IP to log
//      // in to many different user accounts.  We have a reasonably high limit
//      // since there may be only one apparent IP for all users at an institution.
//      if (!$this->flood->isAllowed('user.failed_login_ip', $flood_config->get('ip_limit'), $flood_config->get('ip_window'))) {
//        $form_state->set('flood_control_triggered', 'ip');
//        return;
//      }
//      $accounts = $this->userStorage->loadByProperties(['name' => $form_state->getValue('name'), 'status' => 1]);
//      $account = reset($accounts);
//      if ($account) {
//        if ($flood_config->get('uid_only')) {
//          // Register flood events based on the uid only, so they apply for any
//          // IP address. This is the most secure option.
//          $identifier = $account->id();
//        } else {
//          // The default identifier is a combination of uid and IP address. This
//          // is less secure but more resistant to denial-of-service attacks that
//          // could lock out all users with public user names.
//          $identifier = $account->id() . '-' . $this->getRequest()->getClientIP();
//        }
//        $form_state->set('flood_control_user_identifier', $identifier);
//
//        // Don't allow login if the limit for this user has been reached.
//        // Default is to allow 5 failed attempts every 6 hours.
//        if (!$this->flood->isAllowed('user.failed_login_user', $flood_config->get('user_limit'), $flood_config->get('user_window'), $identifier)) {
//          $form_state->set('flood_control_triggered', 'user');
//          return;
//        }
//      }
//      // We are not limited by flood control, so try to authenticate.
//      // Store $uid in form state as a flag for self::validateFinal().
//      $uid = $this->userAuth->authenticate($form_state->getValue('name'), $password);
//      $form_state->set('uid', $uid);
//    }
  }

  /**
   * Checks if user was not authenticated, or if too many logins were attempted.
   *
   * This validation function should always be the last one.
   */
  public function validateFinal(array &$form, FormStateInterface $form_state)
  {
//    $flood_config = $this->config('user.flood');
//    $error = array();
//    if (!$form_state->get('uid')) {
//      // Always register an IP-based failed login event.
//      $this->flood->register('user.failed_login_ip', $flood_config->get('ip_window'));
//      // Register a per-user failed login event.
//      if ($flood_control_user_identifier = $form_state->get('flood_control_user_identifier')) {
//        $this->flood->register('user.failed_login_user', $flood_config->get('user_window'), $flood_control_user_identifier);
//      }
//
//      if ($flood_control_triggered = $form_state->get('flood_control_triggered')) {
//        if ($flood_control_triggered == 'user') {
//          $error[] = $this->t('There has been more than one failed login attempt for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', 'There have been more than @count failed login attempts for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', [':url' => $this->url('user.pass')]);
//        } else {
//          // We did not find a uid, so the limit is IP-based.
//          $error[] = $this->t('Too many failed login attempts from your IP address. This IP address is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', [':url' => $this->url('user.pass')]);
//        }
//      } else {
//        // Use $form_state->getUserInput() in the error message to guarantee
//        // that we send exactly what the user typed in. The value from
//        // $form_state->getValue() may have been modified by validation
//        // handlers that ran earlier than this one.
//        $user_input = $form_state->getUserInput();
//        $query = isset($user_input['name']) ? ['name' => $user_input['name']] : [];
//        $error[] = $this->t('Unrecognized username or password. <a href=":password">Forgot your password?</a>', [':password' => $this->url('user.pass', [], ['query' => $query])]);
//        $accounts = $this->userStorage->loadByProperties(['name' => $form_state->getValue('name')]);
//        if (!empty($accounts)) {
//          $error[] = $this->t('Login attempt failed for %user.', ['%user' => $form_state->getValue('name')]);
//        } else {
//          // If the username entered is not a valid user,
//          // only store the IP address.
//          $error[] = $this->t('Login attempt failed from %ip.', ['%ip' => $this->getRequest()->getClientIp()]);
//        }
//      }
//    } elseif ($flood_control_user_identifier = $form_state->get('flood_control_user_identifier')) {
//      // Clear past failures for this user so as not to block a user who might
//      // log in and out more than once in an hour.
//      $this->flood->clear('user.failed_login_user', $flood_control_user_identifier);
//    }
//    return $error;
  }

}
