<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\PerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for every performances (Gander) tests of Bamboo Twig.
 */
abstract class BambooTwigPerformanceTestBase extends PerformanceTestBase {

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
   * Warm up every caches (router, theme registry, Twig, ...) of given pages.
   *
   * @param string ...$paths
   *   The paths of pages to warm up.
   */
  protected function warmUp(string ...$paths): void {
    foreach ($paths as $path) {
      $this->drupalGet($path);
    }
  }

  /**
   * Collect performance data of a page.
   *
   * @param string $path
   *   The path of the page to collect performance data from.
   * @param array $options
   *   Options to be forwarded to the url generator, eg. "query".
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectPagePerformanceData(string $path, array $options = []): PerformanceData {
    return $this->collectPerformanceData(function () use ($path, $options) {
      $this->drupalGet($path, $options);
    }, $path);
  }

}
