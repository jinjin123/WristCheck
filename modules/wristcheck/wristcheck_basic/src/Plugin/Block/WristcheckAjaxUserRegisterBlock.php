<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'WristcheckAjaxUserRegisterBlock' block.
 *
 * @Block(
 *  id = "wristcheck_ajax_user_register_block",
 *  admin_label = @Translation("Wristcheck ajax user register block"),
 * )
 */
class WristcheckAjaxUserRegisterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $url = Url::fromRoute('wristcheck_basic.wristcheck_user_register_form');
    $link_options = array(
      'attributes' => array(
        'class' => array(
          'use-ajax',
          'login-popup-form',
        ),
        'data-dialog-type' => 'modal',
      ),
    );
    $url->setOptions($link_options);
    $link = Link::fromTextAndUrl($this->t('Register'), $url)->toString();
    $build = [];
    if (\Drupal::currentUser()->isAnonymous()) {
      $build['login_popup_block']['#markup'] = '<div class="Login-popup-link">' . $link . '</div>';
    }
    $build['login_popup_block']['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $build;
  }

}
