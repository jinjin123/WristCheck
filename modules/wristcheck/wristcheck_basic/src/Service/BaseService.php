<?php


namespace Drupal\wristcheck_basic\Service;


use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseService implements BaseServiceInterface, ContainerInjectionInterface
{

  protected $entityTypeManager;

  /**
   * BrandService constructor.
   * @param EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
  }

  public function queryEntity($type, callable $callback = null)
  {
    $query = $this->entityTypeManager->getStorage('node');
    $query = $query->getQuery();
    if ($callback != null) {
      $query = $callback($query);
    }
    $query = $query->condition('type', $type)
      ->execute();

    return $this->entityTypeManager
      ->getStorage('node')
      ->loadMultiple($query);
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }
}
