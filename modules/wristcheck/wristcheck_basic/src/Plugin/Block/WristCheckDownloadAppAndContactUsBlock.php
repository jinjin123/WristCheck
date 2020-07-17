<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristCheckDownloadAppAndContactUsBlock' block.
 *
 * @Block(
 *  id = "wrist_check_download_app_and_contact_us_block",
 *  admin_label = @Translation("Wrist check download app and contact us block"),
 * )
 */
class WristCheckDownloadAppAndContactUsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'wrist_check_download_app_and_contact_us_block';
     $build['wrist_check_download_app_and_contact_us_block']['#markup'] = 'Implement WristCheckDownloadAppAndContactUsBlock.';

    return $build;
  }

}
