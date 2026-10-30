<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigLoader;

use Drupal\bamboo_twig_loader\TwigExtension\Loader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
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
#[CoversMethod(Loader::class, 'loadEntityRevision')]
#[CoversMethod(Loader::class, 'loadField')]
#[CoversMethod(Loader::class, 'loadCurrentUser')]
#[CoversMethod(Loader::class, 'loadImage')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class DatabasePerformanceTest extends LoaderPerformanceTestBase {

  /**
   * Loading the same thing many times must not add any database query.
   *
   * @dataProvider providerLoaderFunctions
   */
  #[DataProvider('providerLoaderFunctions')]
  public function testLoadRepeatedQueries(string $function): void {
    $this->warmUpLoader($function);

    $once = $this->collectLoaderPerformanceData($function, 1);
    $many = $this->collectLoaderPerformanceData($function, 20);

    // Loading the same thing 20 times should cost exactly the same as
    // loading it once, thanks to the static caches.
    $this->assertSame($once->getQueryCount(), $many->getQueryCount(), sprintf('Repeated bamboo_load_%s() must not trigger additional database queries.', $function));
  }

  /**
   * Loading many different entities must not add any database query.
   */
  public function testLoadEntitiesQueries(): void {
    $this->warmUpLoader('entities');

    $once = $this->collectLoaderPerformanceData('entities', 1);
    $many = $this->collectLoaderPerformanceData('entities', 20);

    // Once in the entity persistent cache, loading 20 different entities
    // should cost exactly the same as loading one.
    $this->assertSame($once->getQueryCount(), $many->getQueryCount(), 'Many different bamboo_load_entity() must be loaded from the entity persistent cache.');
  }

}
