<?php

namespace Drupal\wristcheck_basic\Form;

use Drupal\commerce_price\Price;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

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
      '#value' => $this->t('Add to cart'),
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
    ob_end_clean();
    ob_end_flush();

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
      $productVarEntity = ProductVariation::load($product_var);
      $productVarEntity->set('price', $entity->get('field_price')->getValue());
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

      $order_item = $entity_manager->getStorage('commerce_order_item')->create([
        'type' => $order_item_type,
        'purchased_entity' => (string) $product_var,
        'quantity' => 1, // Amount or quantity to be added to the cart.
        'unit_price' => New Price($price, $currency_code),
        'title' => $entity->get('title')->value
      ]);

      $cart_manager->addOrderItem($cart, $order_item);

      \Drupal::messenger()->deleteAll();
      \Drupal::messenger()->addMessage($this->t('@title added to your cart', [
        '@title' => $entity->get('title')->value
      ]));
    }
  }

}
