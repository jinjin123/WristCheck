<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'AddSecondHandWatchToCartBlock' block.
 *
 * @Block(
 *  id = "add_second_hand_watch_to_cart_block",
 *  admin_label = @Translation("Add second hand watch to cart block"),
 * )
 */
class AddSecondHandWatchToCartBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\wristcheck_basic\Form\SellAddToCartForm');
    $build['form'] = $builtForm;
    return $build;
  }

}
