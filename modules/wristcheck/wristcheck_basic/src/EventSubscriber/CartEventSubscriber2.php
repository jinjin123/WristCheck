<?php
namespace Drupal\wristcheck_basic\EventSubscriber;

use Drupal\commerce_cart\CartManagerInterface;
use Drupal\commerce_cart\Event\CartEntityAddEvent;
use Drupal\commerce_cart\Event\CartEvents;
use Drupal\commerce_order\Adjustment;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\commerce_cart\Event\CartEmptyEvent;

/**
 * Cart Event Subscriber.
 */
class CartEventSubscriber2 implements EventSubscriberInterface {

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The cart manager.
   *
   * @var \Drupal\commerce_cart\CartManagerInterface
   */
  protected $cartManager;

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(MessengerInterface $messenger, CartManagerInterface $cart_manager) {
    $this->messenger = $messenger;
    $this->cartManager = $cart_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      CartEvents::CART_EMPTY => [['emptyCart', 100]],
      CartEvents::CART_ENTITY_ADD => [['entityAddCart', 100]],
    ];
  }

  public function emptyCart(CartEmptyEvent $event) {
     $cart =  $event->getCart();
     $order_id = $cart->id();
     $order = Order::load($order_id);
     $order->set('field_if_need_insurance', 0);
     $order->save();
  }

  public function entityAddCart(CartEntityAddEvent $event) {
    $cart =  $event->getCart();
    $order_id = $cart->id();
    $order = Order::load($order_id);
    $subTotalPrice = $order->getSubtotalPrice();
    $insurance_rate = \Drupal::config('wristcheck_basic.rateSettings')->get('insurance');
    $adjustment_amount = $subTotalPrice->multiply($insurance_rate)->divide('100');
    $adjustment = new Adjustment([
      'type' => 'custom',
      'label' => 'Insurance Plus '. $insurance_rate .'%',
      'amount' => $adjustment_amount,
      'percentage' => bcdiv($insurance_rate, '100', 3),
      'included' => FALSE,
      'locked' => TRUE,
    ]);
    $field_if_need_insurance = $order->get('field_if_need_insurance')->getValue();
    if (isset($field_if_need_insurance[0]['value']) && $field_if_need_insurance[0]['value']) {
      $order->set('field_if_need_insurance', 1);
      $order->setAdjustments([$adjustment]);
      $order->save();
    }
    else {
      $order->set('field_if_need_insurance', 0);
      $order->setAdjustments([]);
      $order->save();
    }
  }
}
