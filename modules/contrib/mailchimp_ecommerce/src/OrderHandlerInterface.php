<?php

namespace Drupal\mailchimp_ecommerce;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;

/**
 * Interface for the Order handler.
 */
interface OrderHandlerInterface {

  /**
   * Gets an order from the current Mailchimp store.
   *
   * @param string $order_id
   *   The order ID.
   *
   * @return object
   *   The order.
   */
  public function getOrder($order_id);

  /**
   * Adds a new order to the current Mailchimp store.
   *
   * @param string $order_id
   *   The order ID.
   * @param array $customer
   *   Associative array of customer information.
   *   - id (string): A unique identifier for the customer.
   * @param array $order
   *   Associative array of order information.
   *   - currency_code (string): The three-letter ISO 4217 currency code.
   *   - order_total (float): The total for the order.
   *   - lines (array): An array of the order's line items.
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/ecommerce/stores/orders/#create-post_ecommerce_stores_store_id_orders
   */
  public function addOrder($order_id, array $customer, array $order);

  /**
   * Updates an existing order in the current Mailchimp store.
   *
   * @param string $order_id
   *   The order ID.
   * @param array $order
   *   Associative array of order information.
   *   - currency_code (string): The three-letter ISO 4217 currency code.
   *   - order_total (float): The total for the order.
   *   - lines (array): An array of the order's line items.
   *
   * @see http://developer.mailchimp.com/documentation/mailchimp/reference/ecommerce/stores/orders/#edit-patch_ecommerce_stores_store_id_orders_order_id
   */
  public function updateOrder($order_id, array $order);

  /**
   * Returns customer and order data formatted for use with Mailchimp.
   *
   * @param \Drupal\commerce_order\Entity\Order $order
   *   The Commerce Order.
   *
   * @param array
   *   Order customer.
   *
   * @return array
   *   Array of order data.
   */
  public function buildOrder(Order $order, array $customer);

  /**
   * Returns product data formatted for use with Mailchimp.
   *
   * @param \Drupal\commerce_order\Entity\OrderItem $item
   *   The Commerce Order Item.
   *
   * @return array
   *   Array of product data.
   */
  public function buildProduct(OrderItem $item);

  /**
   * Builds a Mailchimp order from an Ubercart order.
   *
   * @param UcOrder $order
   *   The Ubercart order.
   *
   * @return object
   *   Order object in a Mailchimp-friendly format.
   */
  public function buildUberOrder(\Drupal\uc_order\Entity\Order $order);

}
