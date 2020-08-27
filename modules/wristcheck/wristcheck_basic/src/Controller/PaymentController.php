<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PaymentController.
 */
class PaymentController extends ControllerBase
{

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index()
  {
    return [
      '#theme' => 'wristcheck_profile_payment_main',
      '#type' => 'markup'
    ];
  }

  public function paysuccess()
  {
    // get the user latest order to show
    $id = \Drupal::currentUser()->id();
    $query = \Drupal::entityQuery('commerce_order')
      ->condition('uid', $id)
      ->sort('created','DESC')
      ->range(0,1);
    $order_ids = $query->execute();
    foreach($order_ids as $value){
      $order_id = $value;
      $order = \Drupal\commerce_order\Entity\Order::load($value);
      foreach ($order->getItems() as $order_item) {
        $product_variation = $order_item->getPurchasedEntity();
        $product = \Drupal\commerce_product\Entity\Product::load($product_variation->product_id->getString());
        $imgUrl = file_create_url($product->field_model_images->entity->getFileUri());
        $variables['pic'] = $imgUrl;
        $variables['order_id'] = $order_id;
      }
    }
    return [
      '#theme' => 'wristcheck_payment_success',
      '#type' => 'markup',
      '#variables' => $variables
    ];
  }

  public function paystep()
  {
    $variables = [];
    return [
      '#theme' => 'wristcheck_payment_step',
      '#type' => 'markup',
      '#variables' => $variables
    ];
  }


}
