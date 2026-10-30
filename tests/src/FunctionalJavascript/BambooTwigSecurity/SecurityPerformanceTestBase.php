<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigSecurity;

use Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigPerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for performances (Gander) tests of Security twig functions.
 */
abstract class SecurityPerformanceTestBase extends BambooTwigPerformanceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'user',
    'bamboo_twig',
    'bamboo_twig_security',
    'bamboo_twig_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Use an authenticated user to bypass the Page Cache, the page itself
    // has a max-age of 0 to bypass the Dynamic Page Cache.
    $this->drupalLogin($this->drupalCreateUser(['access content', 'access user profiles']));
  }

  /**
   * Provides every Security functions to be tested.
   *
   * @return array
   *   The Security functions keyed by name.
   */
  public static function providerSecurityFunctions(): array {
    return [
      'bamboo_has_permission' => ['permission'],
      'bamboo_has_permissions' => ['permissions'],
      'bamboo_has_role' => ['role'],
      'bamboo_has_roles' => ['roles'],
    ];
  }

  /**
   * Warm up a page calling the same Security function once and many times.
   *
   * @param string $function
   *   The Security function to use.
   */
  protected function warmUpSecurity(string $function): void {
    $this->warmUp(
      'bamboo-twig/perf/security/' . $function . '/1',
      'bamboo-twig/perf/security/' . $function . '/20',
    );
  }

  /**
   * Collect performance data of a page calling the same function many times.
   *
   * @param string $function
   *   The Security function to use.
   * @param int $iterations
   *   The number of time the same function will be called by the template.
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectSecurityPerformanceData(string $function, int $iterations): PerformanceData {
    $performance_data = $this->collectPagePerformanceData('bamboo-twig/perf/security/' . $function . '/' . $iterations);
    $this->assertSession()->elementsCount('css', '.test-performance-security .security-item', $iterations);

    return $performance_data;
  }

}
