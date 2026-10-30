<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigLoader;

use Drupal\bamboo_twig_loader\TwigExtension\Loader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
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
#[CoversMethod(Loader::class, 'loadEntityRevision')]
#[CoversMethod(Loader::class, 'loadField')]
#[CoversMethod(Loader::class, 'loadCurrentUser')]
#[CoversMethod(Loader::class, 'loadImage')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class CachePerformanceTest extends LoaderPerformanceTestBase {

  /**
   * Loading the same thing many times must not add any cache lookup.
   *
   * @dataProvider providerLoaderFunctions
   */
  #[DataProvider('providerLoaderFunctions')]
  public function testLoadRepeatedCacheReads(string $function): void {
    $this->warmUpLoader($function);

    $once = $this->collectLoaderPerformanceData($function, 1);
    $many = $this->collectLoaderPerformanceData($function, 20);

    // Once in the static caches, the thing must not be fetched again from any
    // cache backend nor its cache tags validated again.
    $this->assertSame($once->getCacheGetCount(), $many->getCacheGetCount(), sprintf('Repeated bamboo_load_%s() must not trigger additional cache gets.', $function));

    // Cache tags lookup metric is only available since Drupal 11.2.
    // @see https://www.drupal.org/node/3511149
    if (method_exists($many, 'getCacheTagLookupQueryCount')) {
      $this->assertSame($once->getCacheTagLookupQueryCount(), $many->getCacheTagLookupQueryCount(), sprintf('Repeated bamboo_load_%s() must not trigger additional cache tags lookup.', $function));
    }
  }

  /**
   * Loading things must never write, delete or invalidate any cache.
   *
   * @dataProvider providerLoaderFunctions
   */
  #[DataProvider('providerLoaderFunctions')]
  public function testLoadDoesNotAlterCache(string $function): void {
    $this->warmUpLoader($function);

    $performance_data = $this->collectLoaderPerformanceData($function, 20);

    $this->assertSame(0, $performance_data->getCacheSetCount(), sprintf('A warm bamboo_load_%s() must not write in any cache.', $function));
    $this->assertSame(0, $performance_data->getCacheDeleteCount(), sprintf('A bamboo_load_%s() must not delete any cache.', $function));
    $this->assertSame(0, $performance_data->getCacheTagInvalidationCount(), sprintf('A bamboo_load_%s() must not invalidate any cache tags.', $function));
  }

  /**
   * Loading entities must use the entity persistent cache.
   */
  public function testLoadEntityPersistentCache(): void {
    $this->warmUpLoader('entity');

    // Clear the entity persistent cache, so the next request is a cold one.
    $this->container->get('cache.entity')->deleteAll();

    $cold = $this->collectLoaderPerformanceData('entity', 1);
    $warm = $this->collectLoaderPerformanceData('entity', 1);

    // The cold request must store the loaded entity in the persistent cache
    // which then saves database queries on the warm request.
    $this->assertGreaterThan(0, $cold->getCacheSetCount(), 'A cold bamboo_load_entity() must write the entity in the persistent cache.');
    $this->assertSame(0, $warm->getCacheSetCount(), 'A warm bamboo_load_entity() must not write in any cache.');
    $this->assertLessThan($cold->getQueryCount(), $warm->getQueryCount(), 'A warm bamboo_load_entity() must load the entity from the persistent cache.');
  }

  /**
   * Loading many different entities must cost a single cache get per entity.
   */
  public function testLoadEntitiesCacheReads(): void {
    $this->warmUpLoader('entities');

    $once = $this->collectLoaderPerformanceData('entities', 1);
    $many = $this->collectLoaderPerformanceData('entities', 20);

    // Every additional entity is a single entity persistent cache hit.
    $this->assertLessThanOrEqual($once->getCacheGetCount() + 19, $many->getCacheGetCount(), 'Many different bamboo_load_entity() must not trigger more than one cache get per entity.');
    $this->assertSame(0, $many->getCacheSetCount(), 'Many different warm bamboo_load_entity() must not write in any cache.');
  }

}
