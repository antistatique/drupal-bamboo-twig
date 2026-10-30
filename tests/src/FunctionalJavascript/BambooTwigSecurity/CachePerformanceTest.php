<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigSecurity;

use Drupal\bamboo_twig_security\TwigExtension\Security;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests cache performances (Gander) of Security twig functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_gander
 */
#[CoversClass(Security::class)]
#[CoversMethod(Security::class, 'hasPermission')]
#[CoversMethod(Security::class, 'hasPermissions')]
#[CoversMethod(Security::class, 'hasRole')]
#[CoversMethod(Security::class, 'hasRoles')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class CachePerformanceTest extends SecurityPerformanceTestBase {

  /**
   * Calling the same access check many times must not add any cache lookup.
   *
   * @dataProvider providerSecurityFunctions
   */
  #[DataProvider('providerSecurityFunctions')]
  public function testSecurityRepeatedCacheReads(string $function): void {
    $this->warmUpSecurity($function);

    $once = $this->collectSecurityPerformanceData($function, 1);
    $many = $this->collectSecurityPerformanceData($function, 20);

    // Once in the static caches, nothing must be fetched again from any cache
    // backend.
    $this->assertSame($once->getCacheGetCount(), $many->getCacheGetCount(), 'Repeated ' . $this->dataName() . '() must not trigger additional cache gets.');
  }

  /**
   * Calling access check must never write, delete or invalidate any cache.
   *
   * @dataProvider providerSecurityFunctions
   */
  #[DataProvider('providerSecurityFunctions')]
  public function testSecurityDoesNotAlterCache(string $function): void {
    $this->warmUpSecurity($function);

    $performance_data = $this->collectSecurityPerformanceData($function, 20);

    $this->assertSame(0, $performance_data->getCacheSetCount(), 'A warm ' . $this->dataName() . '() must not write in any cache.');
    $this->assertSame(0, $performance_data->getCacheDeleteCount(), 'A ' . $this->dataName() . '() must not delete any cache.');
    $this->assertSame(0, $performance_data->getCacheTagInvalidationCount(), 'A ' . $this->dataName() . '() must not invalidate any cache tags.');
  }

}
