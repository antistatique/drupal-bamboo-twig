<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Security twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_security
 */
class BambooTwigSecurityTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_security',
    'bamboo_twig_test',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    /** @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager */
    $this->entityTypeManager = $this->container->get('entity_type.manager');

    $this->admin_user = $this->drupalCreateUser([
      'access content',
      'administer content types',
      'bypass node access',
      'administer site configuration',
      'view the administration theme',
      'administer menu',
      'access administration pages',
    ]);
    $this->admin_user->addRole('administrator');
    $this->admin_user->save();

    // Add the administrator roles to the default user 1.
    $admin = $this->entityTypeManager->getStorage('user')->load(1);
    $admin->addRole('administrator');
    $admin->save();
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasPermission
   */
  public function testHasPermission() {
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-permission-current');
    $this->assertElementContains('.test-security div.security-permission-current', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permission-admin');
    $this->assertElementContains('.test-security div.security-permission-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permission-nobody');
    $this->assertElementContains('.test-security div.security-permission-nobody', 'FALSE');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-permission-current');
    $this->assertElementContains('.test-security div.security-permission-current', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permission-admin');
    $this->assertElementContains('.test-security div.security-permission-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permission-nobody');
    $this->assertElementContains('.test-security div.security-permission-nobody', 'FALSE');
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasRole
   */
  public function testHasRole() {
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-role-current');
    $this->assertElementContains('.test-security div.security-role-current', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-role-admin');
    $this->assertElementContains('.test-security div.security-role-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-role-nobody');
    $this->assertElementContains('.test-security div.security-role-nobody', 'FALSE');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-role-current');
    $this->assertElementContains('.test-security div.security-role-current', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-role-admin');
    $this->assertElementContains('.test-security div.security-role-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-role-nobody');
    $this->assertElementContains('.test-security div.security-role-nobody', 'FALSE');
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasPermissions
   */
  public function testHasPermissions() {
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-current');
    $this->assertElementContains('.test-security div.security-permissions-current', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-admin');
    $this->assertElementContains('.test-security div.security-permissions-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-user-none-or');
    $this->assertElementContains('.test-security div.security-permissions-user-none-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-user-none-and');
    $this->assertElementContains('.test-security div.security-permissions-user-none-and', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-nobody-or');
    $this->assertElementContains('.test-security div.security-permissions-nobody-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-nobody-and');
    $this->assertElementContains('.test-security div.security-permissions-nobody-and', 'FALSE');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-current');
    $this->assertElementContains('.test-security div.security-permissions-current', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-admin');
    $this->assertElementContains('.test-security div.security-permissions-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-user-none-or');
    $this->assertElementContains('.test-security div.security-permissions-user-none-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-user-none-and');
    $this->assertElementContains('.test-security div.security-permissions-user-none-and', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-nobody-or');
    $this->assertElementContains('.test-security div.security-permissions-nobody-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-permissions-nobody-and');
    $this->assertElementContains('.test-security div.security-permissions-nobody-and', 'FALSE');
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasRoles
   */
  public function testHasRoles() {
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-current');
    $this->assertElementContains('.test-security div.security-roles-current', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-admin');
    $this->assertElementContains('.test-security div.security-roles-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-user-none-or');
    $this->assertElementContains('.test-security div.security-roles-user-none-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-user-none-and');
    $this->assertElementContains('.test-security div.security-roles-user-none-and', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-nobody-or');
    $this->assertElementContains('.test-security div.security-roles-nobody-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-nobody-and');
    $this->assertElementContains('.test-security div.security-roles-nobody-and', 'FALSE');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-security');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-current');
    $this->assertElementContains('.test-security div.security-roles-current', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-admin');
    $this->assertElementContains('.test-security div.security-roles-admin', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-admin-or');
    $this->assertElementContains('.test-security div.security-roles-admin-or', 'TRUE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-user-none-or');
    $this->assertElementContains('.test-security div.security-roles-user-none-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-user-none-and');
    $this->assertElementContains('.test-security div.security-roles-user-none-and', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-nobody-or');
    $this->assertElementContains('.test-security div.security-roles-nobody-or', 'FALSE');

    $this->assertSession()->elementExists('css', '.test-security div.security-roles-nobody-and');
    $this->assertElementContains('.test-security div.security-roles-nobody-and', 'FALSE');
  }

}
