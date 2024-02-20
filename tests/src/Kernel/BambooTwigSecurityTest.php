<?php

namespace Drupal\Tests\bamboo_twig\Kernel;

use Drupal\KernelTests\KernelTestBase;

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

    // Since Drupal 10.2.0 installing the table sequences with the
    // method KernelTestBase::installSchema() is deprecated.
    if (version_compare(\Drupal::VERSION, '10.2.0', '<')) {
      $this->installSchema('system', ['sequences']);
    }

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
