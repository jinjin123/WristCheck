<?php

namespace Drupal\wristcheck_basic\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'WristcheckSignUpForNewsBlock' block.
 *
 * @Block(
 *  id = "wristcheck_sign_up_for_news_block",
 *  admin_label = @Translation("Wristcheck sign up for news block"),
 * )
 */
class WristcheckSignUpForNewsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];


    $webform = \Drupal::entityTypeManager()->getStorage('webform')->load('wristcheck_maintenance_form');
    $maintenance_form = $webform->getSubmissionForm();

    array_push($maintenance_form['#attributes']['class'],'wristcheck_sign_up_for_news_block');

//  $maintenance_form = \Drupal::formBuilder()->getForm('Drupal\wristcheck_basic\Form\MaintenanceForm');
    $variables['maintenance_form'] = render($maintenance_form);

    $build['#variables'] = $variables;

    $build['#theme'] = 'wristcheck_sign_up_for_news_block';
    return $build;
  }

}
