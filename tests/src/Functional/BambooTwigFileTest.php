<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests File twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_file
 */
class BambooTwigFileTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_file',
    'bamboo_twig_path',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_file\TwigExtension\File::extensionGuesser
   */
  public function testExtensionGuesser() {
    $this->drupalGet('/bamboo-twig-file');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-pdf');
    $this->assertElementContains('.test-files div.ext-guesser-pdf', 'pdf');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-word-legacy');
    $this->assertElementContains('.test-files div.ext-guesser-word-legacy', 'doc');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-word');
    $this->assertElementContains('.test-files div.ext-guesser-word', 'docx');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-png');
    $this->assertElementContains('.test-files div.ext-guesser-png', 'png');
  }

  /**
   * @covers Drupal\bamboo_twig_file\TwigExtension\File::UrlAbsolute
   */
  public function testUrlAbsolute() {
    $this->drupalGet('/bamboo-twig-file');

    $this->assertSession()->elementExists('css', '.test-files div.url-module-file-relative');
    $this->assertElementContains('.test-files div.url-module-file-relative', '/bamboo_twig_test/files/antistatique.jpg');

    $this->assertSession()->elementExists('css', '.test-files div.url-module-file-absolute');
    $this->assertElementContains('.test-files div.url-module-file-absolute', 'http://');
    $this->assertElementContains('.test-files div.url-module-file-absolute', '/bamboo_twig_test/files/antistatique.jpg');
  }

}
