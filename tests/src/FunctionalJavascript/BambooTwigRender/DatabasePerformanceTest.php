<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigRender;

use Drupal\bamboo_twig_loader\TwigExtension\Render;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests database performances (Gander) of Render twig functions.
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
class DatabasePerformanceTest extends RenderPerformanceTestBase {

  /**
   * Rendering the same thing many times must stay within a queries budget.
   *
   * @dataProvider providerQueriesBudget
   */
  #[DataProvider('providerQueriesBudget')]
  public function testRenderRepeatedQueries(string $function, int $budget): void {
    $this->warmUp(
      'bamboo-twig/perf/render/' . $function . '/1',
      'bamboo-twig/perf/render/' . $function . '/20',
    );

    $once = $this->collectRenderPerformanceData($function, 1);
    $many = $this->collectRenderPerformanceData($function, 20);

    $this->assertLessThanOrEqual($once->getQueryCount() + 19 * $budget, $many->getQueryCount(), sprintf('Repeated bamboo_render_%s() must not trigger more than %d additional database queries per render.', $function, $budget));
  }

  /**
   * Provides the maximum additional database queries per render.
   *
   * @return array
   *   The Render function and its budget of queries per additional render.
   */
  public static function providerQueriesBudget(): array {
    return [
      'bamboo_render_entity' => ['entity', 0],
      'bamboo_render_field' => ['field', 0],
      'bamboo_render_block' => ['block', 0],
      'bamboo_render_region' => ['region', 0],
      'bamboo_render_views' => ['views', 0],
      // @todo bamboo_render_menu() is not render cached, every call loads the
      //   whole menu tree and checks access of every links.
      'bamboo_render_menu' => ['menu', 16],
      'bamboo_render_image_style' => ['image_style', 0],
      'bamboo_render_form' => ['form', 0],
    ];
  }

}
