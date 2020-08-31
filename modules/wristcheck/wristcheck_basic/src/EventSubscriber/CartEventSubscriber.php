<?php

namespace Drupal\wristcheck_basic\EventSubscriber;

use Drupal\commerce_cart\CartManagerInterface;
use Drupal\commerce_cart\Event\CartEmptyEvent;
use Drupal\commerce_cart\Event\CartEntityAddEvent;
use Drupal\commerce_cart\Event\CartEvents;
use Drupal\commerce_cart\Event\CartOrderItemRemoveEvent;
use Drupal\commerce_cart\Event\CartOrderItemUpdateEvent;
use Drupal\commerce_price\Price;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Cart Event Subscriber.
 */
class CartEventSubscriber implements EventSubscriberInterface {

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
      CartEvents::CART_ENTITY_ADD => [['addToCart', 100]],
      CartEvents::CART_ORDER_ITEM_REMOVE => [['removeOrderItem', 100]]
    ];
  }

  /**
   * Add a related product automatically
   *
   * @param \Drupal\commerce_cart\Event\CartEntityAddEvent $event
   *   The cart add event.
   *
   * @throws \Drupal\Core\TypedData\Exception\ReadOnlyException
   */
  public function addToCart(CartEntityAddEvent $event) {
    /** @var \Drupal\commerce_product\Entity\ProductVariationInterface $product_variation */
    $order_item             = $event->getOrderItem();
    foreach($order_item->toArray()['unit_price'] as $value){
      $unit =  $value['currency_code'];
//      \Drupal::logger('cart')->error('CART'  . json_encode($unit));
    }
//    \Drupal::logger('cart')->error('CART'  . $order_item->toArray()['unit_price']);
    $database = \Drupal::database();
    $result = $database->select('tmpsecondhandproduct','t')
      ->condition('t.uid',\Drupal::currentUser()->id())
      ->fields('t',['price'])
      ->orderBy('t.date','DESC')
      ->execute()
      ->fetchCol();
    if(count($result)>0){
      $unit_price = new Price( $result[0], $unit );
      $order_item->setUnitPrice($unit_price, TRUE);
      $order_item->save();
      $cart = $event->getCart();
      $this->cartManager->updateOrderItem($cart,$order_item);
    }
  }

  public function removeOrderItem(CartOrderItemRemoveEvent $event){
//    \Drupal::logger('cart')->error('CART'  . json_encode($event->getOrderItem()->getUnitPrice()->toArray()['number']));
    $unit_price = $event->getOrderItem()->getUnitPrice()->toArray()['number'];
    $database = \Drupal::database();
    $result = $database->select('tmpsecondhandproduct','t')
      ->condition('t.uid',\Drupal::currentUser()->id())
      ->fields('t',['price'])
      ->orderBy('t.date','DESC')
      ->execute()
      ->fetchCol();
    if(count($result)>0){
      if(($unit_price== $result[0]) ==1){
        $result = $database->delete('tmpsecondhandproduct')
          ->condition('price', $result[0])
          ->condition('uid',\Drupal::currentUser()->id())
          ->execute();
      }
    }
  }
}
