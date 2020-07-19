<?php

namespace Drupal\wristcheck_basic\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the wristcheck_basic module.
 */
class DiscoverControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "wristcheck_basic DiscoverController's controller functionality",
      'description' => 'Test Unit for module wristcheck_basic and controller DiscoverController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests wristcheck_basic functionality.
   */
  public function testDiscoverController() {
    // Check that the basic functions of module wristcheck_basic.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
