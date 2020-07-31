<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckSellingProcessBlock' block.
 *
 * @Block(
 *  id = "wristcheck_selling_process_block",
 *  admin_label = @Translation("Wristcheck selling process block"),
 * )
 */
class WristcheckSellingProcessBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_selling_process_block';
    return $build;
  }

}
