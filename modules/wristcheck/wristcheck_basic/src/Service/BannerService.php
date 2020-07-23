<?php

namespace Drupal\wristcheck_basic\Service;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\file\Entity\File;
use Psy\Util\Str;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BannerService.
 */
class BannerService extends BaseService implements BannerServiceInterface
{

  public function queryBanner(bool $isEnable)
  {
    $result = $this->queryEntity('wristcheck_banner');

    $data = [];

    array_walk($result, function ($node) use (&$data) {
      if ($node instanceof \Drupal\node\Entity\Node) {
        $currentData = [
          'file_url' => NULL,
          'ext' => NULL,
          'link' => NULL,
          'type' => NULL,
        ];
        $fileNid = $node->get('field_wc_banner_file')->getValue();
        $link = $node->get('field_wc_banner_link')->getValue();

        if (sizeof($fileNid) > 0) {
          $file = File::load($fileNid[0]['target_id']);
          if ($file != null) {
            $currentData['file_url'] = $file->url();
            $mineType = $file->getMimeType();
            $currentData['ext'] = $mineType;
            if (str_contains($mineType, 'image')) {
              $currentData['type'] = 'image';
            }
            if (str_contains($mineType, 'video')) {
              $currentData['type'] = 'video';
            }
          }
        }
        if (sizeof($link) > 0) {
          $currentLink = $link[0]['uri'];
          $currentData['link'] = $currentLink;
        }
        array_push($data, $currentData);
      }
    });
    return $data;
  }

}
