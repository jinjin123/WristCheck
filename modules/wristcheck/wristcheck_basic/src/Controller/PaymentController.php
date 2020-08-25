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
    return [
      '#theme' => 'wristcheck_payment_success',
      '#type' => 'markup'
    ];
  }

}
