<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Placeholder twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_placeholder
 */
class BambooTwigPlaceholderTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_placeholder',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_placeholder\TwigExtension\Lorem::loremIpsum
   */
  public function testLoremIpsum() {
    $this->drupalGet('/bamboo-twig-placeholder');

    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-word');
    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-words');
    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-words-random');

    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-sentence');
    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-sentences');
    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-sentences-random');

    $this->assertElementCount('p', 1, '.test-placeholders div.placeholder-lorem-paragraph');
    $this->assertElementCount('p', 10, '.test-placeholders div.placeholder-lorem-paragraphs');
    $this->assertElementIsNotEmpty('.test-placeholders div.placeholder-lorem-paragraphs-random');
  }

}
