<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigCacheable;

use Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigPerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for performances (Gander) tests of Cacheable twig functions.
 */
abstract class CacheablePerformanceTestBase extends BambooTwigPerformanceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'user',
    'bamboo_twig',
    'bamboo_twig_cacheable',
    'bamboo_twig_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Use an authenticated user to bypass the Page Cache, so only the Dynamic
    // Page Cache is altered by the attached cacheable metadata.
    $this->drupalLogin($this->drupalCreateUser(['access content']));
  }

  /**
   * Warm up twice a page attaching cacheable metadata.
   *
   * @param string $metadata
   *   The cacheable metadata to attach, eg. "none", "tags", "max_age", ...
   * @param array $query
   *   An optional query, eg. ['bamboo' => 1].
   */
  protected function warmUpCacheable(string $metadata, array $query = []): void {
    // The first request builds the page, the second one is the first served
    // from caches, so only then every caches are stable.
    $this->drupalGet('bamboo-twig/perf/cacheable/' . $metadata, ['query' => $query]);
    $this->drupalGet('bamboo-twig/perf/cacheable/' . $metadata, ['query' => $query]);
  }

  /**
   * Collect performance data of a page attaching cacheable metadata.
   *
   * @param string $metadata
   *   The cacheable metadata to attach, eg. "none", "tags", "max_age", ...
   * @param array $query
   *   An optional query, eg. ['bamboo' => 1].
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectCacheablePerformanceData(string $metadata, array $query = []): PerformanceData {
    $performance_data = $this->collectPagePerformanceData('bamboo-twig/perf/cacheable/' . $metadata, ['query' => $query]);
    $this->assertSession()->elementExists('css', '.test-performance-cacheable');

    return $performance_data;
  }

}
