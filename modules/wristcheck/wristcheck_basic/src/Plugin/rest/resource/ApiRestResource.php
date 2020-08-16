<?php

namespace Drupal\wristcheck_basic\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Site\Settings;
use Drupal\Core\Cache\CacheableMetadata;


/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "api_rest_resource",
 *   label = @Translation("Api rest resource"),
 *   uri_paths = {
 *     "canonical" = "/currency-price"
 *   }
 * )
 */
class ApiRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('wristcheck_basic');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

    /**
     * Responds to GET requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
//    public function get($payload) {
//
//        // You must to implement the logic of your REST Resource here.
//        // Use current user after pass authentication to validate access.
//        if (!$this->currentUser->hasPermission('access content')) {
//            throw new AccessDeniedHttpException();
//        }
//
//        return new ResourceResponse($payload, 200);
//    }
    public function get(){
      $search = \Drupal::request()->query->get('search');
      $database = \Drupal::database();
      $result = $database->select('currency','c')
        ->condition('c.unit',$search)
        ->fields('c',['rate'])
        ->execute()
        ->fetchAll();
      $response = new ResourceResponse($result[0]->rate);
      $disable_cache = new CacheableMetadata();
      $disable_cache->setCacheMaxAge(0);
      $response->addCacheableDependency($disable_cache);
      return $response;
    }

}
