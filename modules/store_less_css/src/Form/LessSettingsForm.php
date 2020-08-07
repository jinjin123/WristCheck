<?php
/**
 * @file
 * Contains \Drupal\store_less_css\Form\LessSettingsForm.
 */

namespace Drupal\store_less_css\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class LessSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'less_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // Name config file.
    // Значения будут храниться в файле:
    // store_less_css.less.settings.yml
    return ['store_less_css.less.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('store_less_css.less.settings');

    $form['developer_options'] = array(
      '#type' => 'details',
      '#title' => $this->t('Developer Options'),
      '#open' => $config->get('less_devel'),
    );

    $form['developer_options']['less_devel'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Developer Mode'),
      '#description' => $this->t('Enable developer mode to ensure LESS files are regenerated every page load.'),
      '#default_value' => $config->get('less_devel'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('store_less_css.less.settings')
      ->set('less_devel', $values['less_devel'])
      ->save();

  }
}
