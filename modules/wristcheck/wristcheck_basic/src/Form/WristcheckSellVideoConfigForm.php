<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class WristcheckSellVideoConfigForm.
 */
class WristcheckSellVideoConfigForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'wristcheck_basic.wristchecksellvideoconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'wristcheck_sell_video_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('wristcheck_basic.wristchecksellvideoconfig');
    $form['sell_video_details']['link'] = [
      '#type' => 'textfield',
      '#title' => t('Url for video'),
      '#default_value' => $config->get('link'),
      '#description' => t("Links. Examples: node/1.mp4"),
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $this->config('wristcheck_basic.wristchecksellvideoconfig')
      ->set('link', $form_state->getValue('link'))
      ->save();
  }

}
