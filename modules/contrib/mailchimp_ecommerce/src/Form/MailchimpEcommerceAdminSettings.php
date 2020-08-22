<?php

namespace Drupal\mailchimp_ecommerce\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;
use Drupal\mailchimp_ecommerce\StoreHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailchimpEcommerceAdminSettings extends ConfigFormBase {

  /**
   * The Store Handler Interface.
   *
   * @var \Drupal\mailchimp_ecommerce\StoreHandler $store_handler
   */
  protected $store_handler;

  /**
   * MailchimpEcommerceAdminSettings constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Config Factory Interface.
   * @param mixed $store_context
   *   The Store Context Interface.
   * @param \Drupal\mailchimp_ecommerce\StoreHandlerInterface $store_handler
   *   The Store Handler Interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, StoreHandlerInterface $store_handler) {
    parent::__construct($config_factory);

    $this->store_handler = $store_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {

    $static = new static(
      $container->get('config.factory'),
      $container->get('mailchimp_ecommerce.store_handler')
    );

      return $static;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mailchimp_ecommerce_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('mailchimp_ecommerce.settings');

    $settings = [
      'mailchimp_ecommerce_store_name',
      'mailchimp_ecommerce_list_id',
      'mailchimp_ecommerce_store_id',
      'mailchimp_ecommerce_integration',
    ];
    foreach ($settings as $variable) {
      if (isset($form[$variable]['#parents'])) {
        $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
      }
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mailchimp_ecommerce.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['mailchimp_ecommerce_notice'] = [
      '#markup' => t('This page will allow you to create a store. Once created, you cannot change the audience associated with the store.'),
    ];
    $form['mailchimp_ecommerce_store_name'] = [
      '#type' => 'textfield',
      '#title' => t('Store Name'),
      '#required' => TRUE,
      '#default_value' => \Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_store_name'),
      '#description' => t('The name of your store as it should appear in your Mailchimp account.'),
    ];

    $mailchimp_lists = mailchimp_get_lists();
    $list_options = ['' => '-- Select --'];

    foreach ($mailchimp_lists as $list_id => $list) {
      $list_options[$list_id] = $list->name;
    }

    if (!empty(\Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_list_id'))) {
      $existing_store_id = \Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_list_id');
      $form['mailchimp_ecommerce_list_id_existing'] = [
        '#markup' => t('Once created, the audience cannot be changed for a given store. This store is connected to the audience named') . ' ' . $list_options[$existing_store_id],
      ];
    }
    else {
      $form['mailchimp_ecommerce_list_id'] = [
        '#type' => 'select',
        '#title' => t('Store Audience'),
        '#required' => TRUE,
        '#options' => $list_options,
        '#default_value' => \Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_list_id'),
      ];
    }

    $list_options_currency = ['' => '-- Select --'] + mailchimp_ecommerce_get_currency_codes();
    $form['mailchimp_ecommerce_currency'] = [
      '#type' => 'select',
      '#options' => $list_options_currency,
      '#title' => t('Store Currency Code'),
      '#required' => TRUE,
      '#description' => t('This is overridden if you have selected to use the default currency from Commerce.'),
    ];

    $options = ['' => t('-- Select --')];
    $has_images = false;
    $desired_type = '';

    $field_map = \Drupal::entityManager()->getFieldMap();
    $moduleHandler = \Drupal::service('module_handler');
    if ($moduleHandler->moduleExists('mailchimp_ecommerce_commerce')) {
      $desired_type = 'commerce_product';
    }
    elseif ($moduleHandler->moduleExists('mailchimp_ecommerce_ubercart')) {
      $desired_type = 'node';
    }

    $field_definitions = [];
    foreach ($field_map as $entity_type => $fields) {
      $field_definitions[$entity_type] = \Drupal::entityManager()->getFieldStorageDefinitions($entity_type);
    }
    foreach ($field_map as $entity_type => $fields) {
      if ($entity_type == $desired_type) {
        foreach ($fields as $field_name => $field_properties) {
          if ($field_properties['type'] == 'image') {
            $options[$field_name] = $field_name;
            $has_images = true;
          }
        }
      }
    }
    if ($has_images) {
      $form['product_image'] = [
        '#type'        => 'select',
        '#title'       => t('Product Image'),
        '#multiple'    => FALSE,
        '#description' => t('Please choose the image field for your products.'),

        '#options'       => $options,
        '#default_value' => \Drupal::config('mailchimp_ecommerce.settings')->get('product_image'),
        '#required'      => TRUE,
      ];
    }

    if (!empty(\Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_store_id'))) {
      $form['sync'] = [
        '#type' => 'fieldset',
        '#title' => t('Product sync'),
        '#collapsible' => FALSE,
      ];
      $form['sync']['products'] = [
        '#markup' => \Drupal::l(t('Sync existing products to Mailchimp'), Url::fromRoute('mailchimp_ecommerce.sync'))
      ];
      $form['sync-orders'] = [
        '#type' => 'fieldset',
        '#title' => t('Order sync'),
        '#collapsible' => FALSE,
      ];
      $form['sync-orders']['orders'] = [
        '#markup' => \Drupal::l(t('Sync existing orders to Mailchimp'), Url::fromRoute('mailchimp_ecommerce.sync_orders')),
      ];
    }

    $form['platform'] = [
      '#type' => 'hidden',
      '#default_value' => '',
    ];

    $settings_form = parent::buildForm($form, $form_state);

    return $settings_form;
  }

  public function _submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $store_id = \Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_store_id');
    if (\Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_store_id') == NULL) {
      $store_id = mailchimp_ecommerce_generate_store_id();
      \Drupal::configFactory()->getEditable('mailchimp_ecommerce.settings')->set('mailchimp_ecommerce_store_id', $store_id)->save();
    }

    if ($store_id != NULL) {
      $currency = $form_state->getValue(['mailchimp_ecommerce_currency']);

      $platform = !empty($form_state->getValue('platform')) ? $form_state->getValue('platform') : '';

      // Determine if a store is being created or updated.
      $existing_store = $this->store_handler->getStore($store_id);

      if (empty($existing_store)) {
        $store = [
          'list_id' => !$form_state->getValue(['mailchimp_ecommerce_list_id']) ? $form_state->getValue(['mailchimp_ecommerce_list_id']) : \Drupal::config('mailchimp_ecommerce.settings')->get('mailchimp_ecommerce_list_id'),
          'name' => $form_state->getValue(['mailchimp_ecommerce_store_name']),
          'currency_code' => $currency,
        ];

        $this->store_handler->addStore($store_id, $store, $platform);
      }
      else {
        $this->store_handler->updateStore($store_id, $form_state->getValue(['mailchimp_ecommerce_store_name']), $currency, $platform);
      }
    }

  }

}
