<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckoutBlock' block.
 *
 * @Block(
 *  id = "wristcheck_sell_service_block",
 *  admin_label = @Translation("Wristcheck sell service block"),
 * )
 */
class WristcheckSellServiceBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_sell_service_block';

    return $build;
  }

}
