<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFilterProductBlock' block.
 *
 * @Block(
 *  id = "wristcheck_filter_product_block",
 *  admin_label = @Translation("Wristcheck filter product block"),
 * )
 */
class WristcheckFilterProductBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_filter_product_block';

    $entityManager = \Drupal::service('entity_field.manager');
    $fields = $entityManager->getFieldDefinitions('commerce_product', 'watch');

    $fieldLocationDefinition = $fields['field_location']->getFieldStorageDefinition();
    $locations = $fieldLocationDefinition->getSettings()['allowed_values'];

    $currencies = $this->commerceCurrencyRepository->getAll();

    $variables = [
      'locations' => $locations,
      'currencies' => $currencies
    ];

    $build['#variables'] = $variables;
//     $build['wristcheck_filter_product_block']['#markup'] = 'Implement WristcheckFilterProductBlock.';

    return $build;
  }

}
