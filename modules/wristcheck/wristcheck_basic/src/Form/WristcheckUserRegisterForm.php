<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class WristcheckUserRegisterForm.
 */
class WristcheckUserRegisterForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wristcheck_user_register_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // get user login form.
    $form = \Drupal::formBuilder()->getForm('Drupal\user\RegisterForm');

    // add register class.
    $register = Link::fromTextAndUrl($this->t('No user account yet?'), Url::fromRoute('user.register'))->toRenderable();
    $register['#attributes'] = ['class' => 'register'];

    // add forget class.
    $forget = Link::fromTextAndUrl($this->t('No user account yet?'), Url::fromRoute('user.pass'))->toRenderable();
    $forget['#attributes'] = ['class' => 'forget'];

    $form['links'] = [
      '#type' => 'markup',
      '#markup' => '<div class="links">'. render($register) . render($forget) .'</div>',
      '#suffix'=>'<div class="form-footer text-center"><p class="form-footer-title">' . $this->t('Do you not currently have a user account?') . '</p><div><a class="wc-btn-dark"><div class="wc-btn-cont"><span class="fa fa-arrow-right"></span> | <span>' . $this->t('IN THE CONTINUE') . '</span></div></a></div></div>',
      '#weight' => 1000,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
//    foreach ($form_state->getValues() as $key => $value) {
//      // @TODO: Validate fields.
//    }
//    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
//    foreach ($form_state->getValues() as $key => $value) {
//      \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
//    }
  }

}
