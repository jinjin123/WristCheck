<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckValueBlock' block.
 *
 * @Block(
 *  id = "wristcheck_buy_long_block",
 *  admin_label = @Translation("Wristcheck buy long block"),
 * )
 */
class WristcheckBuyLongBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_buy_long_block';

    return $build;
  }

}
