<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigRender;

use Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigPerformanceTestBase;
use Drupal\Tests\PerformanceData;

/**
 * Base class for performances (Gander) tests of Render twig functions.
 */
abstract class RenderPerformanceTestBase extends BambooTwigPerformanceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'user',
    'file',
    'image',
    'block',
    'views',
    'bamboo_twig',
    'bamboo_twig_loader',
    'bamboo_twig_test',
  ];

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

    // Place a block in the region rendered by bamboo_render_region().
    $this->drupalPlaceBlock('system_powered_by_block', ['region' => 'content']);

    // Copy the image used by bamboo_render_image_style().
    $this->container->get('file_system')->copy(
      $this->container->get('extension.list.module')->getPath('bamboo_twig_test') . '/files/antistatique.jpg',
      'public://antistatique.jpg',
    );

    // Use an authenticated user to bypass the Page Cache, the page itself
    // has a max-age of 0 to bypass the Dynamic Page Cache.
    $this->drupalLogin($this->drupalCreateUser([
      'access content',
      'access user profiles',
      'access administration pages',
    ]));
  }

  /**
   * Provides every Render functions to be tested.
   *
   * @return array
   *   The Render functions keyed by name.
   */
  public static function providerRenderFunctions(): array {
    return [
      'bamboo_render_entity' => ['entity'],
      'bamboo_render_field' => ['field'],
      'bamboo_render_block' => ['block'],
      'bamboo_render_region' => ['region'],
      'bamboo_render_views' => ['views'],
      'bamboo_render_menu' => ['menu'],
      'bamboo_render_image_style' => ['image_style'],
      'bamboo_render_image_style with preprocess' => ['image_style_preprocess'],
      'bamboo_render_form' => ['form'],
    ];
  }

  /**
   * Collect performance data of a page rendering the same thing many times.
   *
   * @param string $function
   *   The Render function to use, eg. "entity", "field", "block", ...
   * @param int $iterations
   *   The number of time the same thing will be rendered by the template.
   *
   * @return \Drupal\Tests\PerformanceData
   *   The collected performance data.
   */
  protected function collectRenderPerformanceData(string $function, int $iterations): PerformanceData {
    $performance_data = $this->collectPagePerformanceData('bamboo-twig/perf/render/' . $function . '/' . $iterations);
    $this->assertSession()->elementsCount('css', '.test-performance-render .render-item', $iterations);

    return $performance_data;
  }

}
