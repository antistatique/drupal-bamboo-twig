<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigConfig;

use Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigPerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for performances (Gander) tests of Config twig functions.
 */
abstract class ConfigPerformanceTestBase extends BambooTwigPerformanceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'user',
    'bamboo_twig',
    'bamboo_twig_config',
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
   * Provides every Config functions to be tested.
   *
   * @return array
   *   The Config functions keyed by name.
   */
  public static function providerConfigFunctions(): array {
    return [
      'bamboo_settings_get' => ['settings'],
      'bamboo_config_get' => ['config'],
      'bamboo_state_get' => ['state'],
    ];
  }

  /**
   * Warm up a page calling the same Config function once and many times.
   *
   * @param string $function
   *   The Config function to use.
   */
  protected function warmUpConfig(string $function): void {
    $this->warmUp(
      'bamboo-twig/perf/config/' . $function . '/1',
      'bamboo-twig/perf/config/' . $function . '/20',
    );
  }

  /**
   * Collect performance data of a page calling the same function many times.
   *
   * @param string $function
   *   The Config function to use.
   * @param int $iterations
   *   The number of time the same function will be called by the template.
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectConfigPerformanceData(string $function, int $iterations): PerformanceData {
    $performance_data = $this->collectPagePerformanceData('bamboo-twig/perf/config/' . $function . '/' . $iterations);
    $this->assertSession()->elementsCount('css', '.test-performance-config .config-item', $iterations);

    return $performance_data;
  }

}
