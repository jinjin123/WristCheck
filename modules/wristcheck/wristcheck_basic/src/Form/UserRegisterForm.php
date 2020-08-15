<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\Core\Link;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\HtmlCommand;
/**
 * Class UserLoginForm.
 */
class UserRegisterForm extends FormBase
{
  public function getFormId()
  {
    return 'user_register_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['errors'] = [];
     $form['Email'] = [
      '#type' => 'textfield',
      '#title' => 'Email',
      '#size' => 60,
      '#maxlength' => USERNAME_MAX_LENGTH,
      '#description' => $this->t('Enter your  Email.') ,
      '#required' => TRUE,
      '#attributes' => [
        'autocorrect' => 'none',
        'autocapitalize' => 'none',
        'spellcheck' => 'false',
        'autofocus' => 'autofocus',
        'placeholder' => 'Email',
      ],
    ];

    $form['Name'] = [
      '#type' => 'textfield',
      '#title' => 'Username',
      '#size' => 60,
      '#maxlength' => USERNAME_MAX_LENGTH,
      '#description' => $this->t('Enter your username.' ),
      '#required' => TRUE,
      '#attributes' => [
        'autocorrect' => 'none',
        'autocapitalize' => 'none',
        'spellcheck' => 'false',
        'autofocus' => 'autofocus',
        'placeholder' => 'Username',
      ],
    ];
    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('register'),
      '#submit' => ['::submitForm'],
      '#validate' => ['::validateForm'],
      '#ajax' => array(
        'callback' => '::ajaxRebuildForm',
//        'callback' => array($this, 'validateAjax'),
//        'callback' => 'Drupal\wristcheck_basic\Form\UserRegisterForm::submitForm',
        'event' => 'click',
//        'url' => Url::fromRoute('wristcheck_basic.user_register'),
        'wrapper' => 'reg-form',
        'method' => 'replace',
        'progress' => array(
          'type' => 'throbber',
          'message' => t('Verifying...'),
        ),
      ),
    ];

    $form['#prefix'] = '<div id="reg-form">';
    $form['#suffix'] = '</div>';
//    @TODO: return notice mssage for user when login faild
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_state->setError($form['Name'], $this->t('hello dfsfs.'));
    $form_state->setError($form['Email'], $this->t('hello dsfs.'));
  }

  /**
   * @param array                                $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function ajaxRebuildForm(array $form, FormStateInterface $form_state) {
    $errors = $form_state->getErrors();
    if ($errors) {
      $error_output = '';
      foreach($errors as $error) {
        $err = $error->__toString();
        $error_output .= '<div class="messages error">' . $err .'</div>';
      }
      $form['errors'] = [
        '#markup' => $error_output,
      ];
    }
    return $form;
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
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    return $form;

//    $response = new AjaxResponse();
//    $url = '/my_module/my_controller?selected_value=' . $selectedValue;
//    $response->addCommand(new RedirectCommand($url));
//    return $response;
//    dpm("The AJAX FUNCTION has ran...");
//    \Drupal::logger('User_Register')->error('user register faild
//' . json_encode($form_state));
//    $response = new AjaxResponse();
//    \Drupal::logger('User_Register')->error('user register faild' . json_encode($form_state));
//    if ($form_state->hasAnyErrors()) {
//      $error = $this->t('User Exits, Please change')->render();
//      $response = new AjaxResponse();
//      $response->addCommand(
//        new HtmlCommand(
//          '.result_message',
//          '<div class="result_message">' . 'fdsafsafsaf' . '</div>')
//      );
//    }
//    else {
//      $url = Url::fromRoute('wristcheck_basic.user_controller_useractivate)');
//      $command = new RedirectCommand($url->toString());
//      $response->addCommand($command);
//    }
//    return $response;
  }
//
//  public function UserRegisterCallback(array &$form, FormStateInterface $form_state){
//    $response = new AjaxResponse();
//    $response->addCommand(new HtmlCommand('.result_message', 'user not exits'));
//    return $response;
//  }
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


}
