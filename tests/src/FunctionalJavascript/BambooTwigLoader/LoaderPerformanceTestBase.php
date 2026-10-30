<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigLoader;

use Drupal\FunctionalJavascriptTests\PerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for performances (Gander) tests of Loaders twig functions.
 */
abstract class LoaderPerformanceTestBase extends PerformanceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'user',
    'bamboo_twig',
    'bamboo_twig_loader',
    'bamboo_twig_test',
  ];

  /**
   * We use the minimal profile to keep metrics as stable as possible.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'bamboo_twig_theme_test';

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);
    $this->drupalCreateNode([
      'nid' => 1,
      'type' => 'article',
      'title' => 'News N°1',
    ]);

    // Use an authenticated user to bypass the Page Cache, the page itself
    // has a max-age of 0 to bypass the Dynamic Page Cache.
    $this->drupalLogin($this->drupalCreateUser(['access content']));
  }

  /**
   * Warm up every caches (router, theme registry, Twig, ...) of given pages.
   *
   * @param int[] $iterations
   *   The number of entity loads of each page to warm up.
   */
  protected function warmUp(array $iterations): void {
    foreach ($iterations as $iteration) {
      $this->drupalGet('bamboo-twig/perf/loader/' . $iteration);
    }
  }

  /**
   * Collect performance data of a page loading the same entity many times.
   *
   * @param int $iterations
   *   The number of time the same entity will be loaded by the template.
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectLoaderPerformanceData(int $iterations): PerformanceData {
    $performance_data = $this->collectPerformanceData(function () use ($iterations) {
      $this->drupalGet('bamboo-twig/perf/loader/' . $iterations);
    }, 'bambooTwigLoadEntity' . $iterations);
    $this->assertSession()->elementsCount('css', '.loader-entity-node-1', $iterations);

    return $performance_data;
  }

}
