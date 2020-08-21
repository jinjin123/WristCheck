<?php

namespace Drupal\mailchimp_ecommerce_commerce\Form;

use Drupal\mailchimp_ecommerce\Form\MailchimpEcommerceSync;

class MailchimpEcommerceCommerceSync extends MailchimpEcommerceSync {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function _submitForm($form, $form_state) {
    if (!empty($form_state->getValue('sync_products'))) {
      $batch = [
        'title' => t('Adding products to Mailchimp'),
        'operations' => [],
      ];

      $query = \Drupal::entityQuery('commerce_product');
      $result = $query->execute();

      if (!empty($result)) {
        $product_ids = array_keys($result);

        $batch['operations'][] = [
          '\Drupal\mailchimp_ecommerce_commerce\BatchSyncProducts::syncProducts',
          [$product_ids],
        ];
      }

      batch_set($batch);
    }
  }

}
