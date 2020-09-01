<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckoutBlock' block.
 *
 * @Block(
 *  id = "wristcheck_paymentstep_block",
 *  admin_label = @Translation("Wristcheck download app block"),
 * )
 */
class WristcheckPayStepBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_paymentstep_block';

    return $build;
  }

}
