<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigRender;

use Drupal\bamboo_twig_loader\TwigExtension\Render;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests front-end assets performances (Gander) of Render twig functions.
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
class AssetPerformanceTest extends RenderPerformanceTestBase {

  /**
   * Rendering the same thing many times must not add any CSS or JS.
   *
   * @dataProvider providerRenderFunctions
   */
  #[DataProvider('providerRenderFunctions')]
  public function testRenderRepeatedAssets(string $function): void {
    $this->warmUp(
      'bamboo-twig/perf/render/' . $function . '/1',
      'bamboo-twig/perf/render/' . $function . '/20',
    );

    $once = $this->collectRenderPerformanceData($function, 1);
    $many = $this->collectRenderPerformanceData($function, 20);

    // Libraries attached many times must be aggregated only once.
    $this->assertSame($once->getStylesheetCount(), $many->getStylesheetCount(), sprintf('Repeated bamboo_render_%s() must not add any stylesheet.', $function));
    $this->assertSame($once->getScriptCount(), $many->getScriptCount(), sprintf('Repeated bamboo_render_%s() must not add any script.', $function));
    $this->assertSame($once->getStylesheetBytes(), $many->getStylesheetBytes(), sprintf('Repeated bamboo_render_%s() must not grow stylesheets.', $function));
    $this->assertSame($once->getScriptBytes(), $many->getScriptBytes(), sprintf('Repeated bamboo_render_%s() must not grow scripts.', $function));
  }

  /**
   * Rendering things must only add the CSS & JS they really need.
   *
   * @dataProvider providerAssetsBudget
   */
  #[DataProvider('providerAssetsBudget')]
  public function testRenderAssets(string $function, int $stylesheets, int $scripts): void {
    $this->warmUp('bamboo-twig/perf/render/block/1', 'bamboo-twig/perf/render/' . $function . '/1');

    // The Powered by Drupal block attaches no library, use it as a baseline.
    $baseline = $this->collectRenderPerformanceData('block', 1);
    $performance_data = $this->collectRenderPerformanceData($function, 1);

    $this->assertSame($baseline->getStylesheetCount() + $stylesheets, $performance_data->getStylesheetCount(), sprintf('bamboo_render_%s() must add exactly %d stylesheet(s).', $function, $stylesheets));
    $this->assertSame($baseline->getScriptCount() + $scripts, $performance_data->getScriptCount(), sprintf('bamboo_render_%s() must add exactly %d script(s).', $function, $scripts));
  }

  /**
   * Provides the additional stylesheets & scripts of every Render functions.
   *
   * @return array
   *   The Render function, its additional stylesheets and scripts.
   */
  public static function providerAssetsBudget(): array {
    return [
      'bamboo_render_entity' => ['entity', 0, 0],
      'bamboo_render_field' => ['field', 0, 0],
      'bamboo_render_region' => ['region', 0, 0],
      'bamboo_render_views' => ['views', 0, 0],
      'bamboo_render_menu' => ['menu', 0, 0],
      'bamboo_render_image_style' => ['image_style', 0, 0],
      // Forms attach their own JS libraries (eg. core/drupal.form).
      'bamboo_render_form' => ['form', 0, 1],
    ];
  }

}
