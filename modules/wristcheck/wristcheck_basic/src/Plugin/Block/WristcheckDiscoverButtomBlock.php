<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckoutBlock' block.
 *
 * @Block(
 *  id = "wristcheck_discover_buttom_block",
 *  admin_label = @Translation("Wristcheck discover buttom block"),
 * )
 */
class WristcheckDiscoverButtomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_discover_buttom_block';

    return $build;
  }

}
