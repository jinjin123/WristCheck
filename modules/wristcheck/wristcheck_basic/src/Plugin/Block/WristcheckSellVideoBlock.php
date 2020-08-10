<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckSellVideoBlock' block.
 *
 * @Block(
 *  id = "wristcheck_sell_video_block",
 *  admin_label = @Translation("Wristcheck sell video block"),
 * )
 */
class WristcheckSellVideoBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $config = \Drupal::config('wristcheck_basic.wristchecksellvideoconfig');
    $variables = [
      'link' => $config->get('link')
    ];
    $build = [];
    $build['#variables'] = $variables;
    $build['#theme'] = 'wristcheck_sell_video_block';

    return $build;
  }

}
