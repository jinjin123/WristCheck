<?php

namespace Drupal\wristcheck_basic\Controller;

use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class FaqController.
 */
class FaqController extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return [
      '#theme' => 'wristcheck_faq',
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: index')
    ];
  }

  public function faqauthsystem() {
    $brand = \Drupal::request()->request->get('brand');
    $state = \Drupal::request()->request->get('state');
    $query = \Drupal::entityQuery('commerce_product');
    $query->condition('title', $brand);
//    $query->condition('state', $state );
    $product_ids = $query->execute();
    $productObj = \Drupal\commerce_product\Entity\Product::load(array_values($product_ids)[0]);
//    \Drupal::logger('faq_Check_brand_price')->error('price' . $brand.$state);
    if(!empty($productObj)){
      $price = t('Price is: '. round($productObj->toArray()['field_ask_price'][0]['number'],2));
    }else{
      $price = '';
    }
    $variables['price'] = $price;
    return [
      '#theme' => 'wristcheck_faq_authsystem',
      '#type' => 'markup',
      '#variables' => $variables,
    ];
  }

  public function faqsellstep() {

    return [
      '#theme' => 'wristcheck_faq_sellstep',
      '#type' => 'markup',
    ];
  }

  public function faqauthsystemstep() {
    $user = \Drupal\user\Entity\User::load('1');
    $variables['mail'] = $user->getEmail();
    $variables['phone'] = $user->get('field_phone_number')->value;
    return [
      '#theme' => 'wristcheck_faq_authsystemstep',
      '#type' => 'markup',
      '#variables' => $variables,
    ];
  }

}
