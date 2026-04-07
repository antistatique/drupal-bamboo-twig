<?php

namespace Drupal\Tests\bamboo_twig\Kernel;

use Drupal\bamboo_twig_security\TwigExtension\Security;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests Security twig filters and functions.
 */
#[CoversClass(Security::class)]
#[CoversMethod(Security::class, 'hasPermissions')]
#[CoversMethod(Security::class, 'hasRoles')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_security')]
#[RunTestsInSeparateProcesses]
class BambooTwigSecurityTest extends KernelTestBase {

  /**
   * The Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The Bamboo Twig Security Extension.
   *
   * @var \Drupal\bamboo_twig_security\TwigExtension\Security
   */
  protected $securityExtension;

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
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');

    $this->entityTypeManager = $this->container->get('entity_type.manager');
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
   * Tests hasPermissions() returns TRUE when the user has the given permission.
   */
  public function testHasPermissions() {
    $result = $this->securityExtension->hasPermissions(['bypass node access'], 'OR', 1);
    $this->assertTrue($result);
  }

  /**
   * Tests hasPermissions() returns FALSE when the user lacks the permission.
   */
  public function testHasNotPermissions() {
    $result = $this->securityExtension->hasPermissions(['bypass node access'], 'OR', 2);
    $this->assertFalse($result);
  }

  /**
   * Tests hasRoles() returns TRUE when the user has the given role.
   */
  public function testHasRoles() {
    $result = $this->securityExtension->hasRoles(['administrator'], 'OR', 1);
    $this->assertTrue($result);
  }

  /**
   * Tests hasRoles() returns FALSE when the user lacks the role.
   */
  public function testHasNotRoles() {
    $result = $this->securityExtension->hasRoles(['administrator'], 'OR', 2);
    $this->assertFalse($result);
  }

  /**
   * Tests hasPermissions() throws on an invalid conjunction operator.
   */
  public function testHasPermissionsInvalidConjunction() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid conjunction type "XOR".');
    $this->securityExtension->hasPermissions(['bypass node access'], 'XOR', 1);
  }

  /**
   * Tests hasRoles() throws on an invalid conjunction operator.
   */
  public function testHasRolesInvalidConjunction() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid conjunction type "XOR".');
    $this->securityExtension->hasRoles(['administrator'], 'XOR', 1);
  }

}
