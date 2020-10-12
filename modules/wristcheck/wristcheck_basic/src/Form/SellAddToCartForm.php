<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\commerce_price\Price;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class SellAddToCartForm.
 */
class SellAddToCartForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sell_add_to_cart_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('BUY NEW FROM'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $parameters = \Drupal::routeMatch()->getParameters();
    $entity = $parameters->get('node');

    if (!$entity) {
      return \Drupal::messenger()->addError($this->t('Entity not found!'));
    }

    $product_var = $entity->get('field_sell_item')->target_id;
    $entity_manager = \Drupal::entityTypeManager();

    $cart_manager = \Drupal::service('commerce_cart.cart_manager');
    $cart_provider = \Drupal::service('commerce_cart.cart_provider');

    if ($product_var) {
      $order_type = '2hw_order';
      $order_item_type = '2hw_order_item';
      $store_id = 1;

      $price = $entity->get('field_price')->number;
      $currency_code = $entity->get('field_price')->currency_code;

      $store = $entity_manager->getStorage('commerce_store')->load($store_id);
      $cart = $cart_provider->getCart($order_type, $store);
      if (!$cart) {
        $cart = $cart_provider->createCart($order_type, $store);
      }

      $cart->setItems([]);

      $order_item = $entity_manager->getStorage('commerce_order_item')->create([
        'type' => $order_item_type,
        'purchased_entity' => (string) $product_var,
        'quantity' => 1, // Amount or quantity to be added to the cart.
        'unit_price' => New Price($price, $currency_code),
        'overridden_unit_price' => New Price($price, $currency_code),
        'title' => $entity->get('title')->value,
        'checkout_flow' => 'default',
        'field_accessories_description' => $entity->get('field_accessories_description')->getValue(),
        'field_body' => $entity->get('body')->getValue(),
        'field_have_box' => $entity->get('field_have_box')->getValue(),
        'field_owner_location' => $entity->get('field_owner_location')->getValue(),
        'field_material_pictures' => $entity->get('field_material_pictures')->getValue(),
        'field_product_integrity' => $entity->get('field_product_integrity')->getValue(),
        'field_purchase_date' => $entity->get('field_purchase_date')->getValue(),
        'field_year_of_issue' => $entity->get('field_year_of_issue')->getValue(),
        'field_second_hand_watch_content' => $entity->id()
      ]);

      $cart_manager->addOrderItem($cart, $order_item);
      $order_id = $cart->get('order_id')->value;

      $url = Url::fromRoute('commerce_checkout.form', [
        'commerce_order' => $order_id,
        'step' => 'order_information'
      ]);
      \Drupal::messenger()->deleteAll();
      return $form_state->setRedirectUrl($url);
    }
  }

}
