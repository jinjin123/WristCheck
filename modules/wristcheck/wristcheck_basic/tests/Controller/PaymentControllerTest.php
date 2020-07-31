<?php

namespace Drupal\wristcheck_basic\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Provides automated tests for the wristcheck_basic module.
 */
class PaymentControllerTest extends WebTestBase {

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "wristcheck_basic PaymentController's controller functionality",
      'description' => 'Test Unit for module wristcheck_basic and controller PaymentController.',
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
  public function testPaymentController() {
    // Check that the basic functions of module wristcheck_basic.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
