<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\wristcheck_basic\Service\BrandServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CategoryController.
 */
class CategoryController extends ControllerBase implements ContainerInjectionInterface
{
  protected $brandService;

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('wristcheck_basic.brand')
    );
  }

  /**
   * CategoryController constructor.
   * @param BrandServiceInterface $brandService
   */
  public function __construct(BrandServiceInterface $brandService)
  {
    $this->brandService = $brandService;
  }


  /**
   * Index.
   *
   * @return array
   *   Return Hello string.
   */
  public function index()
  {
//    wristcheck_popular_watch_brands




    $variables = [

    ];

    return [
      '#theme' => 'wristcheck_category',
      '#variables' => $variables
//      '#type' => 'markup',
//      '#markup' => $this->t('Implement method: index')
    ];
  }


}
