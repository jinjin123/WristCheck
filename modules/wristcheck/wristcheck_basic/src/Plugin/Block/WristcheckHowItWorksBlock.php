<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckHowItWorksBlock' block.
 *
 * @Block(
 *  id = "wristcheck_how_it_works_block",
 *  admin_label = @Translation("Wristcheck how it works block"),
 * )
 */
class WristcheckHowItWorksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_how_it_works_block';
//     $build['wristcheck_how_it_works_block']['#markup'] = 'Implement WristcheckHowItWorksBlock.';

    return $build;
  }

}
