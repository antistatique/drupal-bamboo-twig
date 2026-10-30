<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigRender;

use Drupal\bamboo_twig_loader\TwigExtension\Render;
use Drupal\Core\Cache\Cache;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests cache performances (Gander) of Render twig functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_gander
 */
#[CoversClass(Render::class)]
#[CoversMethod(Render::class, 'renderEntity')]
#[CoversMethod(Render::class, 'renderField')]
#[CoversMethod(Render::class, 'renderBlock')]
#[CoversMethod(Render::class, 'renderRegion')]
#[CoversMethod(Render::class, 'renderMenu')]
#[CoversMethod(Render::class, 'renderImageStyle')]
#[CoversMethod(Render::class, 'renderForm')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class CachePerformanceTest extends RenderPerformanceTestBase {

  /**
   * Rendering the same thing many times must stay within a cache gets budget.
   *
   * @dataProvider providerCacheGetsBudget
   */
  #[DataProvider('providerCacheGetsBudget')]
  public function testRenderRepeatedCacheReads(string $function, int $budget): void {
    $this->warmUp(
      'bamboo-twig/perf/render/' . $function . '/1',
      'bamboo-twig/perf/render/' . $function . '/20',
    );

    $once = $this->collectRenderPerformanceData($function, 1);
    $many = $this->collectRenderPerformanceData($function, 20);

    $this->assertLessThanOrEqual($once->getCacheGetCount() + 19 * $budget, $many->getCacheGetCount(), sprintf('Repeated bamboo_render_%s() must not trigger more than %d additional cache gets per render.', $function, $budget));
  }

  /**
   * Provides the maximum additional cache gets per render.
   *
   * @return array
   *   The Render function and its budget of cache gets per additional render.
   */
  public static function providerCacheGetsBudget(): array {
    return [
      // Every render is a Render Cache hit.
      'bamboo_render_entity' => ['entity', 1],
      'bamboo_render_field' => ['field', 0],
      'bamboo_render_block' => ['block', 0],
      'bamboo_render_region' => ['region', 2],
      // Every render is a Views output & results cache hit.
      'bamboo_render_views' => ['views', 3],
      // @todo bamboo_render_menu() is not render cached, every call loads the
      //   whole menu tree and checks access of every links.
      'bamboo_render_menu' => ['menu', 15],
      'bamboo_render_image_style' => ['image_style', 0],
      'bamboo_render_form' => ['form', 0],
    ];
  }

  /**
   * Rendering things must never write, delete or invalidate any cache.
   *
   * @dataProvider providerRenderFunctions
   */
  #[DataProvider('providerRenderFunctions')]
  public function testRenderDoesNotAlterCache(string $function): void {
    $this->warmUp('bamboo-twig/perf/render/' . $function . '/20');

    $performance_data = $this->collectRenderPerformanceData($function, 20);

    $this->assertSame(0, $performance_data->getCacheSetCount(), sprintf('A warm bamboo_render_%s() must not write in any cache.', $function));
    $this->assertSame(0, $performance_data->getCacheDeleteCount(), sprintf('A bamboo_render_%s() must not delete any cache.', $function));
    $this->assertSame(0, $performance_data->getCacheTagInvalidationCount(), sprintf('A bamboo_render_%s() must not invalidate any cache tags.', $function));
  }

  /**
   * Rendering an entity must use the Render Cache.
   */
  public function testRenderEntityRenderCache(): void {
    $this->warmUp('bamboo-twig/perf/render/entity/1');

    // Invalidate only the rendered entity, so the next request re-renders it.
    Cache::invalidateTags(['node:1']);

    $cold = $this->collectRenderPerformanceData('entity', 1);
    $warm = $this->collectRenderPerformanceData('entity', 1);

    // Only the rendered entity carries the "node:1" cache tag, so a write
    // proves it is stored in the Render Cache, then served from it.
    $this->assertGreaterThan(0, $cold->getCacheSetCount(), 'A cold bamboo_render_entity() must write the rendered entity in the Render Cache.');
    $this->assertSame(0, $warm->getCacheSetCount(), 'A warm bamboo_render_entity() must be served from the Render Cache.');
  }

}
