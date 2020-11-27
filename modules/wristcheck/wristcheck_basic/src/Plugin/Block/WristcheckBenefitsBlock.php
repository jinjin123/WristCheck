<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckValueBlock' block.
 *
 * @Block(
 *  id = "wristcheck_buy_benefits_block",
 *  admin_label = @Translation("Wristcheck buy benefits block"),
 * )
 */
class WristcheckBenefitsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_buy_benefits_block';

    return $build;
  }

}
