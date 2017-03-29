<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Files twig filters and functions.
 *
 * @group bamboo_twig
 */
class BambooTwigFilesTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_files',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_files\TwigExtension\Files::extensionGuesser
   */
  public function testExtensionGuesser() {
    $this->drupalGet('/bamboo-twig-files');

    $this->assertElementPresent('.test-files div.ext-guesser');
    $this->assertElementContains('.test-files div.ext-guesser', 'pdf');
  }

  /**
   * @covers Drupal\bamboo_twig_files\TwigExtension\Files::themeUrl
   */
  public function testThemeUrl() {
    $this->drupalGet('/bamboo-twig-files');

    $this->assertElementPresent('.test-files div.theme-url');
    $this->assertElementContains('.test-files div.theme-url', 'core/themes/stable/images/color/hook.png');
  }

}
