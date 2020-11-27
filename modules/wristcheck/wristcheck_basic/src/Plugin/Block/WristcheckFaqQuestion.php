<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckFaqCheckoutBlock' block.
 *
 * @Block(
 *  id = "wristcheck_faq_question_block",
 *  admin_label = @Translation("Wristcheck faq question block"),
 * )
 */
class WristcheckFaqQuestion extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_faq_question_block';

    return $build;
  }

}
