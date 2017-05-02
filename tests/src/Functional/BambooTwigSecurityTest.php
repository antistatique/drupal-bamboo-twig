<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Security twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_security
 */
class BambooTwigSecurityTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_security',
    'bamboo_twig_test',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create a user for tests.
    $this->admin_user = $this->drupalCreateUser([
      'access content',
      'administer content types',
      'bypass node access',
      'administer site configuration',
      'view the administration theme',
      'administer menu',
      'access administration pages',
    ]);
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasPermission
   */
  public function testHasPermission() {
    $this->drupalGet('/bamboo-twig-security');

    $this->assertElementPresent('.test-security div.security-permission-current');
    $this->assertElementContains('.test-security div.security-permission-current', 'FALSE');

    $this->assertElementPresent('.test-security div.security-permission-admin');
    $this->assertElementContains('.test-security div.security-permission-admin', 'TRUE');

    $this->assertElementPresent('.test-security div.security-permission-nobody');
    $this->assertElementContains('.test-security div.security-permission-nobody', 'FALSE');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-security');

    $this->assertElementPresent('.test-security div.security-permission-current');
    $this->assertElementContains('.test-security div.security-permission-current', 'TRUE');

    $this->assertElementPresent('.test-security div.security-permission-admin');
    $this->assertElementContains('.test-security div.security-permission-admin', 'TRUE');

    $this->assertElementPresent('.test-security div.security-permission-nobody');
    $this->assertElementContains('.test-security div.security-permission-nobody', 'FALSE');
  }

  /**
   * @covers Drupal\bamboo_twig_security\TwigExtension\Security::hasRole
   */
  public function testHasRole() {
    $this->drupalGet('/bamboo-twig-security');

    $this->assertElementPresent('.test-security div.security-role-current');
    $this->assertElementContains('.test-security div.security-role-current', 'FALSE');

    $this->assertElementPresent('.test-security div.security-role-admin');
    $this->assertElementContains('.test-security div.security-role-admin', 'TRUE');

    $this->assertElementPresent('.test-security div.security-role-nobody');
    $this->assertElementContains('.test-security div.security-role-nobody', 'FALSE');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-security');

    $this->assertElementPresent('.test-security div.security-role-current');
    $this->assertElementContains('.test-security div.security-role-current', 'TRUE');

    $this->assertElementPresent('.test-security div.security-role-admin');
    $this->assertElementContains('.test-security div.security-role-admin', 'TRUE');

    $this->assertElementPresent('.test-security div.security-role-nobody');
    $this->assertElementContains('.test-security div.security-role-nobody', 'FALSE');
  }

}
