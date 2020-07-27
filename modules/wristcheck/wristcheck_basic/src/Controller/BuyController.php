<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\block\Entity\Block;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\wristcheck_basic\Service\BannerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BuyController.
 */
class BuyController extends ControllerBase implements ContainerInjectionInterface
{
  /**
   * @var BannerServiceInterface
   */
  protected $bannerService;

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('wristcheck_basic.banner')
    );
  }

  /**
   * BuyController constructor.
   * @param BannerServiceInterface $bannerService
   */
  public function __construct(BannerServiceInterface $bannerService)
  {
    $this->bannerService = $bannerService;
  }

  public function index()
  {
    $banners = $this->bannerService->queryBanner(true);
    $explore_wristcheck_view = views_embed_view('wristcheck_buy_explore');
    $popular_brands_view = views_embed_view('wristcheck_buy_popular_brands');
    $news_view = views_embed_view('wristcheck_buy_news');
    $magazine_view = views_embed_view('wristcheck_buy_magazine');
//    $testimdnials_view = views_embed_view('wristcheck_testimdnials');

//    $block = \Drupal\block\Entity\Block::load('wristchecksellingprocess');
//    $wristcheck_selling_process_view = \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);
//
//    $download_block = Block::load('wristcheckdownloadappfollowus');
//    $download_view = \Drupal::entityTypeManager()->getViewBuilder('block')->view($download_block);


    $variables = [
      'banners' => $banners,
      'magazines' => render($magazine_view),
      'explore_wristcheck' => render($explore_wristcheck_view),
      'popular_brands' => render($popular_brands_view),
//      'testimdnials' => render($testimdnials_view),
      'news' => render($news_view),
//      'wristcheck_selling_process' => render($wristcheck_selling_process_view),
//      'download' =>render($download_view)
    ];


    return [
      '#theme' => 'wristcheck_buy',
      '#variables' => $variables,
//      '#type' => 'markup',
//      '#markup' => $this->t('Implement method: index')
    ];
  }

}
