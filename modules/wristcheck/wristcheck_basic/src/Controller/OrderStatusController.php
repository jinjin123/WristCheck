<?php

namespace Drupal\wristcheck_basic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class OrderStatusController.
 */
class OrderStatusController extends ControllerBase {

  /**
   * Index.
   *
   * @param $commerce_order
   * @return string
   *   Return Hello string.
   */
  public function index($user=null,$commerce_order) {
    $result = views_get_view_result('commerce_activity', 'default', $commerce_order, 'commerce_order');
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('commerce_log');
    $rows = [];
    foreach($result as $result) {
      $entity = $result->_entity;
      $pre_render = $view_builder->view($entity, 'default');
      $render_output = render($pre_render)->__toString();
      $create_day = date('m-d', $entity->get('created')->value);
      $create_hour = date('H:i', $entity->get('created')->value);
      $author = array_values($entity->get('uid')->referencedEntities())[0];
//      $author_link = Link::fromTextAndUrl($author->get('name')->value, Url::fromRoute('entity.user.canonical', array('user' => $entity->id())));

      $rows[] = [
        [
        'data' => [
            '#markup' => "<div><span class='create_day'>{$create_day}</span><span class='create_hour'>{$create_hour}</span></div>"
          ],
        ],
        'message' => [
            'data' => [
              '#markup' => $render_output
            ]
          ],
        'author' => $author->get('name')->value
      ];
      $header = ['Date','Process', 'Administrator'];
    }

    $build['table'] = [
      '#type' => 'table',
      '#title' => 'hello world',
      '#rows' => $rows,
      '#html' => TRUE,
      '#header'=> $header,
      '#empty' => t('No content has been found.'),
    ];
    return $build;
  }


  public function buyOrderStatus($user=null, $commerce_order) {
    $results = views_get_view_result('commerce_activity', 'default', $commerce_order, 'commerce_order');
    $output = '';
    foreach($results as $result)  {
      $entity = $result->_entity;
      $create_day = date('m-d', $entity->get('created')->value);
      $create_hour = date('i:s', $entity->get('created')->value);
      $log = $entity->label();
      $output .= '<div><span class="log-day">'.$create_day . '</span><span class="log-hour">' . $create_hour . '</span>
<span class="log-content">' . $log . '</span></div>';
    }

    return new JsonResponse([
      'output' => $output,
    ]);
  }

}
