<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\block\Entity\Block;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\wristcheck_basic\Service\BannerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BuyController.
 */
class BuyController extends ControllerBase implements ContainerInjectionInterface
{
  /**
   * @var BannerServiceInterface
   */
  protected $bannerService;

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('wristcheck_basic.banner')
    );
  }

  /**
   * BuyController constructor.
   * @param BannerServiceInterface $bannerService
   */
  public function __construct(BannerServiceInterface $bannerService)
  {
    $this->bannerService = $bannerService;
  }

  public function index()
  {
    return [
      '#theme' => 'wristcheck_buy',
//      '#type' => 'markup',
//      '#markup' => $this->t('Implement method: index')
    ];
  }

}
