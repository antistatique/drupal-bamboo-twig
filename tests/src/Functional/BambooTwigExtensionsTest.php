<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Configs twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_extensions
 */
class BambooTwigExtensionsTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_extensions',
    'bamboo_twig_test',
  ];

  /**
   * Cover the \Twig_Extensions_Extension_Text::twig_truncate_filter.
   */
  public function testTextTruncate() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementPresent('.test-extensions div.text-truncat-1');
    $this->assertElementContains('.test-extensions div.text-truncat-1', 'Th...');

    $this->assertElementPresent('.test-extensions div.text-truncat-2');
    $this->assertElementContains('.test-extensions div.text-truncat-2', 'This i...');

    $this->assertElementPresent('.test-extensions div.text-truncat-3');
    $this->assertElementContains('.test-extensions div.text-truncat-3', 'This...');

    $this->assertElementPresent('.test-extensions div.text-truncat-4');
    $this->assertElementContains('.test-extensions div.text-truncat-4', 'This[...]');

    $this->assertElementPresent('.test-extensions div.text-truncat-5');
    $this->assertElementContains('.test-extensions div.text-truncat-5', 'This is a very long sen...');

    $this->assertElementPresent('.test-extensions div.text-truncat-6');
    $this->assertElementContains('.test-extensions div.text-truncat-6', 'This is a very long sentence.', 23, TRUE, '...', 'This is a very long sentence.');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Array::twig_shuffle_filter.
   */
  public function testArrayShuffle() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementPresent('.test-extensions div.array-shuffle-1');
    $this->assertElementContains('.test-extensions div.array-shuffle-1', '1');
    $this->assertElementContains('.test-extensions div.array-shuffle-1', '2');
    $this->assertElementContains('.test-extensions div.array-shuffle-1', '3');

    $this->assertElementPresent('.test-extensions div.array-shuffle-2');
    $this->assertElementContains('.test-extensions div.array-shuffle-2', 'orange');
    $this->assertElementContains('.test-extensions div.array-shuffle-2', 'apple');
    $this->assertElementContains('.test-extensions div.array-shuffle-2', 'citrus');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeAgo() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-ago-1', '1 second ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-2', '5 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-3', '1 minute ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-4', '5 minutes ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-5', '1 hour ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-6', '9 hours ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-7', '6 hours ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-8', '4 days ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-9', '30 days ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-10', '1 month ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-11', '5 months ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-12', '1 year ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-13', '3 years ago');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeIn() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-in-1', 'in 1 second');
    $this->assertElementContains('.test-extensions div.date-diff-in-2', 'in 5 seconds');
    $this->assertElementContains('.test-extensions div.date-diff-in-3', 'in 1 minute');
    $this->assertElementContains('.test-extensions div.date-diff-in-4', 'in 5 minutes');
    $this->assertElementContains('.test-extensions div.date-diff-in-5', 'in 1 hour');
    $this->assertElementContains('.test-extensions div.date-diff-in-6', 'in 9 hours');
    $this->assertElementContains('.test-extensions div.date-diff-in-7', 'in 1 day');
    $this->assertElementContains('.test-extensions div.date-diff-in-8', 'in 5 days');
    $this->assertElementContains('.test-extensions div.date-diff-in-9', 'in 1 month');
    $this->assertElementContains('.test-extensions div.date-diff-in-10', 'in 6 months');
    $this->assertElementContains('.test-extensions div.date-diff-in-11', 'in 1 year');
    $this->assertElementContains('.test-extensions div.date-diff-in-12', 'in 3 years');
  }

}
