<?php

namespace Drupal\wristcheck_basic\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the wristcheck_basic module.
 */
class FaqControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "wristcheck_basic FaqController's controller functionality",
      'description' => 'Test Unit for module wristcheck_basic and controller FaqController.',
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
  public function testFaqController() {
    // Check that the basic functions of module wristcheck_basic.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
