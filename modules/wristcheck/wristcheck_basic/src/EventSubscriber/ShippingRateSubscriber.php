<?php

namespace Drupal\wristcheck_basic\EventSubscriber;

use Drupal\commerce_order\Event\OrderEvent;
use Drupal\commerce_order\Event\OrderEvents;
use Drupal\commerce_shipping\Event\ShippingRatesEvent;
use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ShippingRateSubscriber.
 */
class ShippingRateSubscriber implements EventSubscriberInterface {

  /**
   * commerce log storeage.
   *
   * @var
   */
  protected $logStorage;

  /**
   * ShippingRateSubscriber constructor.
   *
   * @param $entity_type_manager
   */
  public function __construct($entity_type_manager) {
    $this->logStorage = $entity_type_manager->getStorage('commerce_log');
  }

  /**
   * define events.
   *
   * @return array
   */
  public static function getSubscribedEvents() {
    $events = [
      'commerce_shipment.finalize.post_transition' => ['onFinalizeTransition'],
      'commerce_shipment.ship.post_transition' => ['onShipTransition'],
    ];
    return $events;
  }

  public function onFinalizeTransition(WorkflowTransitionEvent $event) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $event->getEntity();
    $array = $order->get('order_id')->referencedEntities();
    $orderEntity = reset($array);
    $this->logStorage->generate($orderEntity, 'order_finalize')->save();
  }

  public function onShipTransition(WorkflowTransitionEvent $event) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $event->getEntity();
    $array = $order->get('order_id')->referencedEntities();
    $orderEntity = reset($array);
    $this->logStorage->generate($orderEntity, 'order_ship')->save();
  }
}
