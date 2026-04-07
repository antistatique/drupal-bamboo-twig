<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\bamboo_twig_file\TwigExtension\File;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests File twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 */
#[CoversClass(File::class)]
#[CoversMethod(File::class, 'extensionGuesser')]
#[CoversMethod(File::class, 'UrlAbsolute')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_file')]
#[RunTestsInSeparateProcesses]
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
   * Tests extensionGuesser() returns the correct extension for various files.
   */
  public function testExtensionGuesser() {
    $this->drupalGet('/bamboo-twig-file');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-pdf');
    $this->assertSession()->elementContains('css', '.test-files div.ext-guesser-pdf', 'pdf');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-word-legacy');
    $this->assertSession()->elementContains('css', '.test-files div.ext-guesser-word-legacy', 'doc');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-word');
    $this->assertSession()->elementContains('css', '.test-files div.ext-guesser-word', 'docx');

    $this->assertSession()->elementExists('css', '.test-files div.ext-guesser-png');
    $this->assertSession()->elementContains('css', '.test-files div.ext-guesser-png', 'png');
  }

  /**
   * Tests UrlAbsolute() returns relative and absolute file URLs.
   */
  public function testUrlAbsolute() {
    $this->drupalGet('/bamboo-twig-file');

    $this->assertSession()->elementExists('css', '.test-files div.url-module-file-relative');
    $this->assertSession()->elementContains('css', '.test-files div.url-module-file-relative', '/bamboo_twig_test/files/antistatique.jpg');

    $this->assertSession()->elementExists('css', '.test-files div.url-module-file-absolute');
    $this->assertSession()->elementContains('css', '.test-files div.url-module-file-absolute', 'http://');
    $this->assertSession()->elementContains('css', '.test-files div.url-module-file-absolute', '/bamboo_twig_test/files/antistatique.jpg');
  }

}
