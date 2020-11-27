<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckoutBlock' block.
 *
 * @Block(
 *  id = "wristcheck_sell_lowsale_block",
 *  admin_label = @Translation("Wristcheck sell lowsale block"),
 * )
 */
class WristcheckSellLowSale  extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_sell_lowsale_block';

    return $build;
  }

}
