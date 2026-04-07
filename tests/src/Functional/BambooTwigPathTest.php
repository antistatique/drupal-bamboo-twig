<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\bamboo_twig_path\TwigExtension\Path;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests Path twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 */
#[CoversClass(Path::class)]
#[CoversMethod(Path::class, 'getSystemPath')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_path')]
#[RunTestsInSeparateProcesses]
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
   * Tests getSystemPath() resolves theme, profile, and module paths.
   */
  public function testPathSystem() {
    $this->drupalGet('/bamboo-twig-path');

    $this->assertSession()->elementExists('css', '.test-paths div.path-theme');
    $this->assertSession()->elementContains('css', '.test-paths div.path-theme', 'core/themes/starterkit_theme');

    $this->assertSession()->elementExists('css', '.test-paths div.path-profile');
    $this->assertSession()->elementContains('css', '.test-paths div.path-profile', 'core/profiles/standard');

    $this->assertSession()->elementExists('css', '.test-paths div.path-module');
    $this->assertSession()->elementContains('css', '.test-paths div.path-module', 'core/modules/node');
  }

}
