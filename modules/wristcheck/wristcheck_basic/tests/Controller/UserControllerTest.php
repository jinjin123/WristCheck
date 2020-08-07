<?php

namespace Drupal\wristcheck_basic\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the wristcheck_basic module.
 */
class UserControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "wristcheck_basic UserController's controller functionality",
      'description' => 'Test Unit for module wristcheck_basic and controller UserController.',
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
  public function testUserController() {
    // Check that the basic functions of module wristcheck_basic.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
