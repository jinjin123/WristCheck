<?php

/**
 * @file
 * Contains \Drupal\nvs_func\Twig\getCurrentNodeId.
 */

namespace Drupal\nvs_func\Twig;

/**
 * Provides the NodeViewCount debugging function within Twig templates.
 */
class CurrentNodeIdExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'getCurrentNodeId';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('getCurrentNodeId', array($this, 'getCurrentNodeId'), array(
        'is_safe' => array('html'),
        
      )),
    );
  }

  /**
   * Provides Kint function to Twig templates.
   *
   * Handles 0, 1, or multiple arguments.
   *
   * Code derived from https://github.com/barelon/CgKintBundle.
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function getCurrentNodeId(){
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
        $nid = $node->nid->value;
        return $nid;
    }else{
      return null;
    }
  }
  

}
