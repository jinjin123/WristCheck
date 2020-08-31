<?php

namespace Drupal\wristcheck_basic\EventSubscriber;

use Drupal\commerce_cart\CartManagerInterface;
use Drupal\commerce_cart\Event\CartEntityAddEvent;
use Drupal\commerce_cart\Event\CartEvents;
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
      CartEvents::CART_ENTITY_ADD => [['addToCart', 100]]
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
//    echo("aaaaa");
    $product_variation = $event->getEntity();
    if ($product_variation->getSku() === '213') {
      var_dump($event->getEntity());
      $product_variation = $event->getEntity();
      $cart = $event->getCart();
      $entity_manager = \Drupal::entityTypeManager();
      // Load a known other product variation.
//      $variation =\Drupal::entityManager()->getStorage('commerce_product_variation')->load(20);
//      $variation = \Drupal::entityTypeManager()->getStorage('commerce_product_variation')->load(15)->toArray();
      $variation = \Drupal::entityTypeManager()->getStorage('commerce_product_variation')->load(15);

      $order_item_option_2 = $entity_manager->getStorage('commerce_order_item')->create([
        'type' => 'default', // Also, Commerce 2.x have a feature to define custom "line item types".
        'purchased_entity' => $variation ,
        'quantity' => 3, // Amount or quantity to be added to the cart.
        'unit_price' => new Price(66,"USD"),
      ]);
      $order_item_option_2 ->save();
//      $this->cart_manager->addOrderItem($cart, $order_item_option_2);
//      $this->cartManager->addEntity($cart, $variation);
      // Create a new order item based on the loaded variation.
//      $new_order_item = $this->cartManager->createOrderItem($variation);
//      $new_order_item->setQuantity(1);

      // Add it to the cart.
      $this->cartManager->addOrderItem($cart, $order_item_option_2);

    }
  }

}
