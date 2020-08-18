<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckValueBlock' block.
 *
 * @Block(
 *  id = "wristcheck_faq_checkvalue_block",
 *  admin_label = @Translation("Wristcheck download app block"),
 * )
 */
class WristcheckFaqCheckValueBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_faq_checkvalue_block';

    return $build;
  }

}
