<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckBannerBlock' block.
 *
 * @Block(
 *  id = "wristcheck_banner_block",
 *  admin_label = @Translation("Wristcheck banner block"),
 * )
 */
class WristcheckBannerBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $bannerService = \Drupal::service('wristcheck_basic.banner');

    $banners = $bannerService->queryBanner();

    $build = [];
    $build['#theme'] = 'wristcheck_banner_block';
    $build['#banners'] = $banners;
//    $build['wristcheck_banner_block']['#markup'] = 'Implement WristcheckBannerBlock.';

    return $build;
  }

}
