<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigSecurity;

use Drupal\bamboo_twig_security\TwigExtension\Security;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests database performances (Gander) of Security twig functions.
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
class DatabasePerformanceTest extends SecurityPerformanceTestBase {

  /**
   * Calling the same access check many times must not add any database query.
   *
   * @dataProvider providerSecurityFunctions
   */
  #[DataProvider('providerSecurityFunctions')]
  public function testSecurityRepeatedQueries(string $function): void {
    $this->warmUpSecurity($function);

    $once = $this->collectSecurityPerformanceData($function, 1);
    $many = $this->collectSecurityPerformanceData($function, 20);

    // Calling the same function 20 times should cost exactly the same as
    // calling it once, thanks to the static caches.
    $this->assertSame($once->getQueryCount(), $many->getQueryCount(), 'Repeated ' . $this->dataName() . '() must not trigger additional database queries.');
  }

}
