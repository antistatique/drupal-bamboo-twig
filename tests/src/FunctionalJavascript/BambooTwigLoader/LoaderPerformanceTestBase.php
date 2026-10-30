<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigLoader;

use Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigPerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for performances (Gander) tests of Loaders twig functions.
 */
abstract class LoaderPerformanceTestBase extends BambooTwigPerformanceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'user',
    'file',
    'image',
    'bamboo_twig',
    'bamboo_twig_loader',
    'bamboo_twig_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Create 20 Nodes to detect any N+1 queries when loading many entities.
    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);
    for ($nid = 1; $nid <= 20; $nid++) {
      $this->drupalCreateNode([
        'nid' => $nid,
        'type' => 'article',
        'title' => 'News N°' . $nid,
      ]);
    }

    // Copy the image used by bamboo_load_image().
    $this->container->get('file_system')->copy(
      $this->container->get('extension.list.module')->getPath('bamboo_twig_test') . '/files/antistatique.jpg',
      'public://antistatique.jpg',
    );

    // Use an authenticated user to bypass the Page Cache, the page itself
    // has a max-age of 0 to bypass the Dynamic Page Cache.
    $this->drupalLogin($this->drupalCreateUser(['access content']));
  }

  /**
   * Provides every Loader functions loading the same thing many times.
   *
   * @return array
   *   The Loader functions keyed by name.
   */
  public static function providerLoaderFunctions(): array {
    return [
      'bamboo_load_entity' => ['entity'],
      'bamboo_load_entity_revision' => ['entity_revision'],
      'bamboo_load_field' => ['field'],
      'bamboo_load_currentuser' => ['current_user'],
      'bamboo_load_image' => ['image'],
    ];
  }

  /**
   * Warm up a page loading the same thing once and many times.
   *
   * @param string $function
   *   The Loader function to use, eg. "entity", "field", "image", ...
   */
  protected function warmUpLoader(string $function): void {
    $this->warmUp(
      'bamboo-twig/perf/loader/' . $function . '/1',
      'bamboo-twig/perf/loader/' . $function . '/20',
    );
  }

  /**
   * Collect performance data of a page loading the same thing many times.
   *
   * @param string $function
   *   The Loader function to use, eg. "entity", "field", "image", ...
   * @param int $iterations
   *   The number of time the same thing will be loaded by the template.
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectLoaderPerformanceData(string $function, int $iterations): PerformanceData {
    $performance_data = $this->collectPagePerformanceData('bamboo-twig/perf/loader/' . $function . '/' . $iterations);
    $this->assertSession()->elementsCount('css', '.test-performance-loader .loader-item', $iterations);

    return $performance_data;
  }

}
