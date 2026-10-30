<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigLoader;

use Drupal\bamboo_twig_loader\TwigExtension\Loader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests database performances (Gander) of Loaders twig functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_gander
 */
#[CoversClass(Loader::class)]
#[CoversMethod(Loader::class, 'loadEntity')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class DatabasePerformanceTest extends LoaderPerformanceTestBase {

  /**
   * Loading the same entity many times must not add any database query.
   */
  public function testLoadEntityRepeatedQueries(): void {
    $this->warmUp([1, 20]);

    $once = $this->collectLoaderPerformanceData(1);
    $many = $this->collectLoaderPerformanceData(20);

    // Loading the same entity 20 times should cost exactly the same as
    // loading it once, thanks to the entity storage static cache.
    $this->assertSame($once->getQueryCount(), $many->getQueryCount(), 'Repeated bamboo_load_entity() must not trigger additional database queries.');
  }

}
