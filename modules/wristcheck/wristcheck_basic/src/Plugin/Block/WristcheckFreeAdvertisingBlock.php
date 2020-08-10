<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFreeAdvertisingBlock' block.
 *
 * @Block(
 *  id = "wristcheck_free_advertising_block",
 *  admin_label = @Translation("Wristcheck free advertising block"),
 * )
 */
class WristcheckFreeAdvertisingBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_free_advertising_block';

    return $build;
  }

}
