<?php
/**
 * @file
 * Contains \Drupal\store_less_css\Controller\LessGenerateController.
 */

namespace Drupal\store_less_css\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * LessGenerateController.
 */
class LessGenerateController {

  /**
   *
   */
  public function get_less($cache_id = NULL) {
    $request = Request::createFromGlobals();
    $less_file = $request->query->get('less_file');
    if(!$less_file){
      throw new NotFoundHttpException();
    }

    $less_request = Request::create($less_file);
    $config = \Drupal::config('store_less_css.less.settings');

    if($config->get('less_devel')){
      $css_patch = _store_less_css_generateCSS($less_request->getPathInfo(), $cache_id, TRUE);

     // drupal_set_message(t('LESS files are being checked for modifications on every request. Remember to <a href=":url">turn off</a> this feature on production websites.'), 'warning');
    }else{
      drupal_set_message(t('LESS: Cleaning Required cache!'), 'error');
      $css_patch = _store_less_css_generateCSS($less_request->getPathInfo(), $cache_id, FALSE);
    }

    if(!$css_patch){
      throw new NotFoundHttpException();
    }

    $response = new BinaryFileResponse($css_patch);
    $response->headers->set('Content-Type', 'text/css');
    return $response;
  }
}
