<?php

namespace Drupal\wristcheck_basic\Service;

/**
 * Class BannerService.
 */
class BannerService implements BannerServiceInterface
{

  public function queryBanner(bool $isEnable)
  {
    return \Drupal::entityQuery('wristcheck_banner')
      ->condition('field_wc_banner_enable', $isEnable)
      ->execute();
  }
}
