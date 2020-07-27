<?php

namespace Drupal\wristcheck_basic\TwigExtension;

//https://www.drupal.org/project/commerce_wishlist/issues/3013963
/**
 * Class CommerceWishlistExtension.
 */
class CommerceWishlistExtension extends \Twig_Extension
{


  /**
   * {@inheritdoc}
   */
  public function getTokenParsers()
  {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getNodeVisitors()
  {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters()
  {
    return [
      new \Twig_SimpleFilter('isInWishList', array($this, 'isInWishList'))
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getTests()
  {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions()
  {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getOperators()
  {
    return [];
  }

  public function isInWishList($product)
  {
    $wishlistProvider = \Drupal::service('commerce_wishlist.wishlist_provider');

    foreach ($wishlistProvider->getWishlists()[1]->getItems() as $item) {
      if ($item->purchasable_entity->getValue()[0]['target_id'] === $product) {
        return true;
      }
    }

    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'wristcheck_basic_commerce_wishlist.twig.extension';
  }

}
