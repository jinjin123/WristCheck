<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSettingsForm.
 */
class UserSettingsForm extends FormBase
{

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'user_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $existUser = User::load($this->currentUser->id());

    $form['pay_by_password'] = [
      '#prefix' => '<div>Payment</div>',
      '#type' => 'checkbox',
      '#title' => $this->t('Pay by password'),
      '#weight' => '0',
      '#default_value' => $existUser->field_pay_by_password->value,
    ];

    $form['pay_by_email_authentication'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Pay by email authentication'),
      '#weight' => '0',
      '#default_value' => $existUser->field_pay_by_email_authenticatio->value,
    ];
    $form['cell_number_to_receive_order_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Cell number to receive order status'),
      '#weight' => '0',
      '#default_value' => $existUser->field_cell_number_to_receive_ord->value,
    ];
    $form['email_to_receive_order_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('email to receive order status'),
      '#weight' => '0',
      '#default_value' => $existUser->field_email_to_receive_order_sta->value,
    ];


    $form['newsletter'] = [
      '#prefix' => '<div>Newsletter</div>',
      '#type' => 'checkbox',
      '#title' => $this->t('newsletter'),
      '#description' => $this->t('(All information about WRISTCHECK will be sent to your mailbox)'),
      '#weight' => '0',
      '#default_value' => $existUser->field_newsletter->value,
    ];
    $form['we_cooperate_in'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('We cooperate in'),
      '#description' => $this->t('(We will receive products from our partnets one after another)'),
      '#weight' => '0',
      '#default_value' => $existUser->field_we_cooperate_in->value,
    ];
    $form['guide'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('guide'),
      '#description' => $this->t('(Tips and tricks to help you make the most of the site)'),
      '#weight' => '0',
      '#default_value' => $existUser->field_guide->value,
    ];
    $form['price_warning'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Price warning'),
      '#description' => $this->t('(Information about price changes in the goods you store)'),
      '#weight' => '0',
      '#default_value' => $existUser->field_price_warning->value,
    ];
    $form['select_the_language'] = [
      '#type' => 'language_select',
      '#title' => $this->t('Select the language'),
      '#weight' => '0',
      '#default_value' => array($existUser->preferred_langcode->value),
    ];

//    $locationarr = $existUser->field_location->getSetting('allowed_values');
//    $taglocation = [];
//    foreach($locationarr  as $key => $value){
//      switch ($value){
//        case "Hong Kong":
//          $optstr  = '<img value='.$value.'data-iconurl="themes-icon.png">'.$value.'</option>';
//          array_push($taglocation, $optstr);
//        case "US":
//          $optstr  = '<option value='.$value.'data-iconurl="themes-icon.png">'.$value.'</option>';
//          array_push($taglocation, $optstr);
//        case "China":
//          $optstr  = '<option value='.$value.'data-iconurl="themes-icon.png">'.$value.'</option>';
//          array_push($taglocation, $optstr);
//      }
//
//    }
    $form['select_the_location'] = [
      '#type' => 'select',
      '#title' => $this->t('Select the location'),
      '#weight' => '0',
      '#default_value' => array($existUser->field_location->value),
      '#options' => array($existUser->field_location->getSetting('allowed_values')),
      '#attributes' => array('class' => array('wc-location-selector')),
    ];

    $form['select_the_currency'] = [
      '#type' => 'select',
      '#title' => $this->t('Select the currency'),
      '#weight' => '0',
      '#default_value' => array($existUser->field_currency->value),
      '#options' => array($existUser->field_currency->getSetting('allowed_values')),

    ];


    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    $form['#redirect'] = FALSE;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $map = [
      'pay_by_password' => 'field_pay_by_password',
      'pay_by_email_authentication' => 'field_pay_by_email_authenticatio',
      'cell_number_to_receive_order_status' => 'field_cell_number_to_receive_ord',
      'email_to_receive_order_status' => 'field_email_to_receive_order_sta',
      'newsletter' => 'field_newsletter',
      'we_cooperate_in' => 'field_we_cooperate_in',
      'guide' => 'field_guide',
      'price_warning' => 'field_price_warning',
      'select_the_language' => 'preferred_langcode',
      'select_the_location' => 'field_location',
      'select_the_currency' => 'field_currency',
    ];

    $existUser = User::load($this->currentUser->id());

    $entityTypeManager = \Drupal::entityTypeManager()->getStorage('profile');
    $entitys = $entityTypeManager->load(\Drupal::currentUser()->id());
    $mail = $existUser->get("mail")->value;
    $fname = $entitys->address->family_name;
    $lname = $entitys->address->given_name;


    $values = $form_state->getValues();


    array_walk($map, function ($value, $key) use ($lname, $fname, $mail, $existUser, $values) {
      if (isset($values[$key])) {
        $existUser->$value->value = $values[$key];
        if($values['newsletter'] == 1){
//          \Drupal::service("wristcheck_basic.mailchamp")->MailChampSubscript($mail,$fname,$lname);
        }
      }
    });

    $existUser->save();
    $form_state->disableRedirect();
    return $form;
  }

}
