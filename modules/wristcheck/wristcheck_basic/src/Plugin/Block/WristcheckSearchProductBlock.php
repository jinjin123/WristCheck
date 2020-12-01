<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\commerce_price\Entity\Currency;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\Entity\ConfigEntityType;
use Drupal\Core\Field\FieldConfigInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'WristcheckSearchProductBlock' block.
 *
 * @Block(
 *  id = "wristcheck_search_product_block",
 *  admin_label = @Translation("Wristcheck search product block"),
 * )
 */
class WristcheckSearchProductBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * Drupal\Core\Entity\EntityManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Symfony\Component\DependencyInjection\ContainerAwareInterface definition.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerAwareInterface
   */
  protected $entityQuery;

  /**
   * Drupal\commerce\ConfigurableFieldManagerInterface definition.
   *
   * @var \Drupal\commerce\ConfigurableFieldManagerInterface
   */
  protected $commerceConfigurableFieldManager;

  protected $commerceCurrencyRepository;

  protected $commerceAttributeFieldManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityManager = $container->get('entity.manager');
    $instance->entityQuery = $container->get('entity.query');
    $instance->commerceConfigurableFieldManager = $container->get('commerce.configurable_field_manager');
    $instance->commerceCurrencyRepository = $container->get('commerce_price.currency_repository');
    $instance->commerceAttributeFieldManager = $container->get('commerce_product.attribute_field_manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $build = [];
    $build['#theme'] = 'wristcheck_search_product_block';
    $entityManager = \Drupal::service('entity_field.manager');
    $fields = $entityManager->getFieldDefinitions('commerce_product', 'watch');

    $fieldLocationDefinition = $fields['field_location']->getFieldStorageDefinition();
    $locations = $fieldLocationDefinition->getSettings()['allowed_values'];

    $currencies = $this->commerceCurrencyRepository->getAll();


    $database = \Drupal::database();
    $query = $database->select('node_field_data', 'n');
    $brands = $query->condition('n.status', '1', '=')
      ->condition('n.type', 'brand', '=')
      ->fields('n', ['nid', 'title'])
      ->orderBy('created', 'desc')
      ->execute()
      ->fetchAll();

    $models = $database->select('commerce_product_field_data', 'n')
      ->condition('n.status', '1', '=')
      ->condition('n.type', 'watch', '=')
      ->fields('n', ['product_id', 'title'])
      ->orderBy('created', 'desc')
      ->execute()
      ->fetchAll();

    $years = $database->select('commerce_product__field_year', 'n')
      ->condition('n.bundle', 'watch', '=')
      ->condition('n.deleted', '0', '=')
      ->fields('n', ['field_year_value'])
      ->distinct()
      ->execute()
      ->fetchAll();

//    $case_diameter = $database->select('commerce_product__field_case_diameter', 'n')
//      ->condition('n.bundle', 'watch', '=')
//      ->condition('n.deleted', '0', '=')
//      ->fields('n', ['field_case_diameter_value'])
//      ->distinct()
//      ->execute()
//      ->fetchAll();

    $variables = [
      'years' => $years,
      'brands' => $brands,
      'currencies' => $currencies,
//      'case_diameter' => $case_diameter,
      'locations' => $locations,
      'models' => $models,
    ];

    $build['#variables'] = $variables;
    return $build;
  }

}
