<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Path twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_path
 */
class BambooTwigPathTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_path',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_path\TwigExtension\Path::getSystemPath
   */
  public function testPathSystem() {
    $this->drupalGet('/bamboo-twig-path');

    $this->assertSession()->elementExists('css', '.test-paths div.path-theme');
    $this->assertElementContains('.test-paths div.path-theme', 'core/themes/starterkit_theme');

    $this->assertSession()->elementExists('css', '.test-paths div.path-profile');
    $this->assertElementContains('.test-paths div.path-profile', 'core/profiles/standard');

    $this->assertSession()->elementExists('css', '.test-paths div.path-module');
    $this->assertElementContains('.test-paths div.path-module', 'core/modules/node');
  }

}
