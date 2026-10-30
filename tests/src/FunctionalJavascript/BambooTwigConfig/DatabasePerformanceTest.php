<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigConfig;

use Drupal\bamboo_twig_config\TwigExtension\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests database performances (Gander) of Config twig functions.
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
class DatabasePerformanceTest extends ConfigPerformanceTestBase {

  /**
   * Calling the same configuration many times must not add any database query.
   *
   * @dataProvider providerConfigFunctions
   */
  #[DataProvider('providerConfigFunctions')]
  public function testConfigRepeatedQueries(string $function): void {
    $this->warmUpConfig($function);

    $once = $this->collectConfigPerformanceData($function, 1);
    $many = $this->collectConfigPerformanceData($function, 20);

    // Calling the same function 20 times should cost exactly the same as
    // calling it once, thanks to the static caches.
    $this->assertSame($once->getQueryCount(), $many->getQueryCount(), 'Repeated ' . $this->dataName() . '() must not trigger additional database queries.');
  }

}
