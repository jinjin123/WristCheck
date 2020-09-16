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
//     $build['wristcheck_filter_product_block']['#markup'] = 'Implement WristcheckFilterProductBlock.';

    return $build;
  }

}
