<?php

namespace Drupal\Tests\bamboo_twig\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\TestTools\PhpUnitCompatibility\RunnerVersion;

/**
 * Tests Security twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_security
 */
class BambooTwigSecurityTest extends KernelTestBase {

  /**
   * The Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'system',
    'user',
    'bamboo_twig',
    'bamboo_twig_security',
  ];

  /**
   * Gets the current drupal core version.
   *
   * @return array
   *   Associative array of version info:
   *   - major: Major version (e.g., "8").
   *   - minor: Minor version (e.g., "0").
   *   - patch: Patch version (e.g., "0").
   *   - extra: Extra version info (e.g., "alpha2").
   *   - extra_text: The text part of "extra" (e.g., "alpha").
   *   - extra_number: The number part of "extra" (e.g., "2").
   */
  protected function getVersionStringToTest(): array {
    return _install_get_version_info(\Drupal::VERSION);
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $version = $this->getVersionStringToTest();

    // In Drupal 9.1 calling KernelTestBase::installSchema() for the tables
    // key_value and key_value_expire is deprecated.
    // @see https://www.drupal.org/node/3143286
    // @todo update when dropping support below Drupal:9.0.
    $system_tables = ['sequences'];
    if ($version['major'] === '8' || ($version['major'] === '9' && $version['minor'] === '1')) {
      $system_tables[] = 'key_value';
    }
    $this->installSchema('system', $system_tables);

    /** @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager */
    $this->entityTypeManager = $this->container->get('entity_type.manager');

    /** @var \Drupal\bamboo_twig_security\TwigExtension\Security $securityExtension */
    $this->securityExtension = $this->container->get('bamboo_twig_security.twig.security');

    // Create admin user.
    $adminUser = $this->entityTypeManager->getStorage('user')->create([
      'uid'    => 1,
      'mail'   => 'admin',
      'name'   => 'admin',
      'status' => 1,
    ]);
    $adminUser->addRole('administrator');
    $adminUser->save();

    // Create anonymous user.
    $adminUser = $this->entityTypeManager->getStorage('user')->create([
      'uid'    => 2,
      'mail'   => 'anonymous',
      'name'   => 'anonymous',
      'status' => 1,
    ]);
    $adminUser->save();
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasPermissions
   */
  public function testHasPermissions() {
    $result = $this->securityExtension->hasPermissions(['bypass node access'], 'OR', 1);
    $this->assertTrue($result);
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasPermissions
   */
  public function testHasNotPermissions() {
    $result = $this->securityExtension->hasPermissions(['bypass node access'], 'OR', 2);
    $this->assertFalse($result);
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasRoles
   */
  public function testHasRoles() {
    $result = $this->securityExtension->hasRoles(['administrator'], 'OR', 1);
    $this->assertTrue($result);
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasRoles
   */
  public function testHasNotRoles() {
    $result = $this->securityExtension->hasRoles(['administrator'], 'OR', 2);
    $this->assertFalse($result);
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasPermissions
   */
  public function testHasPermissionsInvalidConjunction() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid conjunction type "XOR".');
    $this->securityExtension->hasPermissions(['bypass node access'], 'XOR', 1);
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasRoles
   */
  public function testHasRolesInvalidConjunction() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid conjunction type "XOR".');
    $this->securityExtension->hasRoles(['administrator'], 'XOR', 1);
  }

}
