<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Path twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_path
 */
class BambooTwigPathTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_path',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_path\TwigExtension\Path::getSystemPath
   */
  public function testPathSystem() {
    $this->drupalGet('/bamboo-twig-path');

    $this->assertElementPresent('.test-paths div.path-theme');
    $this->assertElementContains('.test-paths div.path-theme', 'core/themes/stable');

    $this->assertElementPresent('.test-paths div.path-core');
    $this->assertElementContains('.test-paths div.path-core', 'core');

    $this->assertElementPresent('.test-paths div.path-module');
    $this->assertElementContains('.test-paths div.path-module', 'core/modules/node');
  }

}
