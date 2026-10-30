<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigLoader;

use Drupal\bamboo_twig_loader\TwigExtension\Loader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests cache performances (Gander) of Loaders twig functions.
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
class CachePerformanceTest extends LoaderPerformanceTestBase {

  /**
   * Loading the same entity many times must not add any cache lookup.
   */
  public function testLoadEntityRepeatedCacheReads(): void {
    $this->warmUp([1, 20]);

    $once = $this->collectLoaderPerformanceData(1);
    $many = $this->collectLoaderPerformanceData(20);

    // Once in the entity storage static cache, the entity must not be fetched
    // again from any cache backend nor its cache tags validated again.
    $this->assertSame($once->getCacheGetCount(), $many->getCacheGetCount(), 'Repeated bamboo_load_entity() must not trigger additional cache gets.');

    // Cache tags lookup metric is only available since Drupal 11.2.
    // @see https://www.drupal.org/node/3511149
    if (method_exists($many, 'getCacheTagLookupQueryCount')) {
      $this->assertSame($once->getCacheTagLookupQueryCount(), $many->getCacheTagLookupQueryCount(), 'Repeated bamboo_load_entity() must not trigger additional cache tags lookup.');
    }
  }

  /**
   * Loading entities must never write, delete or invalidate any cache.
   */
  public function testLoadEntityDoesNotAlterCache(): void {
    $this->warmUp([20]);

    $performance_data = $this->collectLoaderPerformanceData(20);

    $this->assertSame(0, $performance_data->getCacheSetCount(), 'A warm bamboo_load_entity() must not write in any cache.');
    $this->assertSame(0, $performance_data->getCacheDeleteCount(), 'A bamboo_load_entity() must not delete any cache.');
    $this->assertSame(0, $performance_data->getCacheTagInvalidationCount(), 'A bamboo_load_entity() must not invalidate any cache tags.');
  }

  /**
   * Loading entities must use the entity persistent cache.
   */
  public function testLoadEntityPersistentCache(): void {
    $this->warmUp([1]);

    // Clear the entity persistent cache, so the next request is a cold one.
    $this->container->get('cache.entity')->deleteAll();

    $cold = $this->collectLoaderPerformanceData(1);
    $warm = $this->collectLoaderPerformanceData(1);

    // The cold request must store the loaded entity in the persistent cache
    // which then saves database queries on the warm request.
    $this->assertGreaterThan(0, $cold->getCacheSetCount(), 'A cold bamboo_load_entity() must write the entity in the persistent cache.');
    $this->assertSame(0, $warm->getCacheSetCount(), 'A warm bamboo_load_entity() must not write in any cache.');
    $this->assertLessThan($cold->getQueryCount(), $warm->getQueryCount(), 'A warm bamboo_load_entity() must load the entity from the persistent cache.');
  }

}
