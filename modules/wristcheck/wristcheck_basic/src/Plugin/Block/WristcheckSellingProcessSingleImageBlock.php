<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckSellingProcessSingleImageBlock' block.
 *
 * @Block(
 *  id = "wristcheck_selling_process_single_image_block",
 *  admin_label = @Translation("Wristcheck selling process single image block"),
 * )
 */
class WristcheckSellingProcessSingleImageBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_selling_process_single_image_block';

    return $build;
  }

}
