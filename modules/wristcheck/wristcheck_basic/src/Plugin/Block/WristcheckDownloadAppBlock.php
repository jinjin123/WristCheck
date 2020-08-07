<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckDownloadAppBlock' block.
 *
 * @Block(
 *  id = "wristcheck_download_app_block",
 *  admin_label = @Translation("Wristcheck download app block"),
 * )
 */
class WristcheckDownloadAppBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wristcheck_download_app_block';
//     $build['wristcheck_download_app_block']['#markup'] = 'Implement WristcheckDownloadAppBlock.';
    return $build;
  }

}
