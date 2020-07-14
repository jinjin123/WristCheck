<?php

namespace Drupal\Tests\maintenance200\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Test maintenance 200.
 *
 * @group maintenance200
 */
class Maintenance200Test extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'maintenance200',
    'node',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create Basic page node type.
    if ($this->profile != 'standard') {
      $this->drupalCreateContentType([
        'type' => 'page',
        'name' => 'Basic page',
        'display_submitted' => FALSE,
      ]);
    }

    $node = $this->drupalCreateNode([
      'title' => $this->t('Maintenance 200 tests.'),
      'type' => 'page',
      'promote' => 1,
    ]);
    $this->container->get('router.builder')->rebuild();

    $this->config('system.site')->set('page.front', '/node/' . $node->id())->save();
  }

  /**
   * Site not in maintenance mode.
   *
   * Make sure status code is 200 when maintenance mode is off.
   */
  public function testStatusCodeHomePageNormal() {
    \Drupal::state()->set('system.maintenance_mode', FALSE);

    $this->drupalGet('<front>');
    $this->assertResponse(200);
  }

  /**
   * Site in maintenance mode, maintenance200 disabled.
   *
   * Make sure status code is 503 when maintenance mode is on and module status
   * is disabled.
   */
  public function testStatusCodeHomePageMaintenance503() {
    \Drupal::state()->set('system.maintenance_mode', TRUE);

    $this->config('maintenance200.settings')
      ->set('maintenance200_enabled', 0)
      ->set('maintenance200_status_code', 418)
      ->save();

    $this->drupalGet('<front>');
    $this->assertResponse(503);
  }

  /**
   * Site in maintenance mode, maintenance200 set to 200.
   *
   * Make sure status code is when maintenance mode is on and module status is
   * enabled with 200 code.
   */
  public function testStatusCodeHomePageMaintenance200() {
    \Drupal::state()->set('system.maintenance_mode', TRUE);

    $this->config('maintenance200.settings')
      ->set('maintenance200_enabled', 1)
      ->set('maintenance200_status_code', 200)
      ->save();

    $this->drupalGet('<front>');
    $this->assertResponse(200);
  }

  /**
   * Site in maintenance mode, module200 set to 418.
   *
   * Make sure status code is 418 when maintenance mode is on and module status
   * is enabled with 418 code.
   */
  public function testStatusCodeHomePageMaintenance418() {
    \Drupal::state()->set('system.maintenance_mode', TRUE);

    $this->config('maintenance200.settings')
      ->set('maintenance200_enabled', 1)
      ->set('maintenance200_status_code', 418)
      ->save();

    $this->drupalGet('<front>');
    $this->assertResponse(418);
  }

}
