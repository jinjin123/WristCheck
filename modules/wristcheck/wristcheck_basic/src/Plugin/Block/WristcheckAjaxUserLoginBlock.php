<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'WristcheckAjaxUserLoginBlock' block.
 *
 * @Block(
 *  id = "wristcheck_ajax_user_login_block",
 *  admin_label = @Translation("Wristcheck ajax user login block"),
 * )
 */
class WristcheckAjaxUserLoginBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $url = Url::fromRoute('wristcheck_basic.user_login_form');
    $link_options = array(
      'attributes' => array(
        'class' => array(
//          'use-ajax',
          'login-popup-form',
        ),
        'data-dialog-options' => Json::encode([
          'width' => 730,
          'padding' => 20,
          'dialogClass' => 'wc-login-dialog'
        ]),
//        'data-progress-type' => 'WCFullprogress',
//        'data-dialog-type' => 'modal'
      ),
    );
    $url->setOptions($link_options);
    $link = Link::fromTextAndUrl($this->t('Login'), $url)->toString();
    $build = [];
    if (\Drupal::currentUser()->isAnonymous()) {
      $build['login_popup_block']['#markup'] = '<div class="Login-popup-link">' . $link . '</div>';
    }
    $build['login_popup_block']['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $build;
  }

}
