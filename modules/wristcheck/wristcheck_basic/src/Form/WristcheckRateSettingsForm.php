<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for this site.
 */
class WristcheckRateSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wristcheck_basic_rate_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'wristcheck_basic.rateSettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('wristcheck_basic.rateSettings');


    $form['insurance'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Insurance(%)'),
      '#description' => '',
      '#size' => 5,
      '#default_value' => $config->get('insurance') ? : NULL,
    );

    $form['shipping'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Shipping(%)'),
      '#description' => '',
      '#size' => 5,
      '#default_value' => $config->get('shipping') ? : NULL,
    );

    $form['service_center'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Service center(%)'),
      '#description' => '',
      '#size' => 5,
      '#default_value' => $config->get('service_center') ? : NULL,
    );

    $form['overhead_costs'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Overhead costs(%)'),
      '#description' => '',
      '#size' => 5,
      '#default_value' => $config->get('overhead_costs') ? : NULL,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration
    $this->configFactory->getEditable('wristcheck_basic.rateSettings')
      // Set the submitted configuration setting
      ->set('insurance', $form_state->getValue('insurance'))
      ->set('shipping', $form_state->getValue('shipping'))
      ->set('service_center', $form_state->getValue('service_center'))
      ->set('overhead_costs', $form_state->getValue('overhead_costs'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
