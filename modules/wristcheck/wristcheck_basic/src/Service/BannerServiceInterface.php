<?php

namespace Drupal\wristcheck_basic\Service;

/**
 * Interface BannerServiceInterface.
 */
interface BannerServiceInterface extends BaseServiceInterface
{
  public function queryBanner(bool $isEnable);
}
