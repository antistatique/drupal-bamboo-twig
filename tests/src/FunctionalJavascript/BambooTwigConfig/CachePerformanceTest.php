<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigConfig;

use Drupal\bamboo_twig_config\TwigExtension\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests cache performances (Gander) of Config twig functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_gander
 */
#[CoversClass(Config::class)]
#[CoversMethod(Config::class, 'getSettings')]
#[CoversMethod(Config::class, 'getConfig')]
#[CoversMethod(Config::class, 'getState')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class CachePerformanceTest extends ConfigPerformanceTestBase {

  /**
   * Calling the same configuration many times must not add any cache lookup.
   *
   * @dataProvider providerConfigFunctions
   */
  #[DataProvider('providerConfigFunctions')]
  public function testConfigRepeatedCacheReads(string $function): void {
    $this->warmUpConfig($function);

    $once = $this->collectConfigPerformanceData($function, 1);
    $many = $this->collectConfigPerformanceData($function, 20);

    // Once in the static caches, nothing must be fetched again from any cache
    // backend.
    $this->assertSame($once->getCacheGetCount(), $many->getCacheGetCount(), 'Repeated ' . $this->dataName() . '() must not trigger additional cache gets.');
  }

  /**
   * Calling configuration must never write, delete or invalidate any cache.
   *
   * @dataProvider providerConfigFunctions
   */
  #[DataProvider('providerConfigFunctions')]
  public function testConfigDoesNotAlterCache(string $function): void {
    $this->warmUpConfig($function);

    $performance_data = $this->collectConfigPerformanceData($function, 20);

    $this->assertSame(0, $performance_data->getCacheSetCount(), 'A warm ' . $this->dataName() . '() must not write in any cache.');
    $this->assertSame(0, $performance_data->getCacheDeleteCount(), 'A ' . $this->dataName() . '() must not delete any cache.');
    $this->assertSame(0, $performance_data->getCacheTagInvalidationCount(), 'A ' . $this->dataName() . '() must not invalidate any cache tags.');
  }

}
