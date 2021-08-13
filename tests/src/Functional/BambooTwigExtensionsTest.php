<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Extensions twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_extensions
 */
class BambooTwigExtensionsTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_extensions',
    'bamboo_twig_test',
  ];

  /**
   * Cover the \Twig_Extensions_Extension_Text::twig_truncate_filter.
   */
  public function testTextTruncate() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertSession()->elementExists('css', '.test-extensions div.text-truncat-1');
    $this->assertElementContains('.test-extensions div.text-truncat-1', 'Th...');

    $this->assertSession()->elementExists('css', '.test-extensions div.text-truncat-2');
    $this->assertElementContains('.test-extensions div.text-truncat-2', 'This i...');

    $this->assertSession()->elementExists('css', '.test-extensions div.text-truncat-3');
    $this->assertElementContains('.test-extensions div.text-truncat-3', 'This...');

    $this->assertSession()->elementExists('css', '.test-extensions div.text-truncat-4');
    $this->assertElementContains('.test-extensions div.text-truncat-4', 'This[...]');

    $this->assertSession()->elementExists('css', '.test-extensions div.text-truncat-5');
    $this->assertElementContains('.test-extensions div.text-truncat-5', 'This is a very long sen...');

    $this->assertSession()->elementExists('css', '.test-extensions div.text-truncat-6');
    $this->assertElementContains('.test-extensions div.text-truncat-6', 'This is a very long sentence.', 23, TRUE, '...', 'This is a very long sentence.');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Array::twig_shuffle_filter.
   */
  public function testArrayShuffle() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertSession()->elementExists('css', '.test-extensions div.array-shuffle-1');
    $this->assertElementContains('.test-extensions div.array-shuffle-1', '1');
    $this->assertElementContains('.test-extensions div.array-shuffle-1', '2');
    $this->assertElementContains('.test-extensions div.array-shuffle-1', '3');

    $this->assertSession()->elementExists('css', '.test-extensions div.array-shuffle-2');
    $this->assertElementContains('.test-extensions div.array-shuffle-2', 'orange');
    $this->assertElementContains('.test-extensions div.array-shuffle-2', 'apple');
    $this->assertElementContains('.test-extensions div.array-shuffle-2', 'citrus');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeAgoAuto() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-ago-1', '1 second ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-2', '5 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-3', '1 minute ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-4', '5 minutes ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-5', '1 hour ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-6', '9 hours ago');
    $this->assertElementContains('.test-extensions div.date-diff-ago-7', '1 day ago');
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
  public function testDateDiffTimeInAuto() {
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

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeAgoForcedUnit() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-1', '1 second ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-2', '0.016666666666667 minute ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-3', '5 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-4', '60 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-5', '1 minute ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-6', '302 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-7', '3660 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-8', '32702 seconds ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-9', '0.27152777777778 day ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-10', '4.2715277777778 days ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-11', '30.271527777778 days ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-12', '1.0416666666667 month ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-13', '6.1083333333333 months ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-14', '391.27152777778 days ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-15', '37.408333333333 months ago');
    $this->assertElementContains('.test-extensions div.date-diff-unit-ago-16', '3.072553045859 years ago');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeInForcedUnit() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-unit-in-1', 'in 1 second');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-2', 'in 5 seconds');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-3', 'in 1 minute');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-4', 'in 302 seconds');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-5', 'in 61 minutes');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-6', 'in 32702 seconds');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-7', 'in 1 day');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-8', 'in 0.16666666666667 month');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-9', 'in 1.0666666666667 month');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-10', 'in 189 days');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-11', 'in 13.233333333333 months');
    $this->assertElementContains('.test-extensions div.date-diff-unit-in-12', 'in 3.088295687885 years');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeAgoForcedUnitNotHumanized() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-1', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-2', '-0.016666666666667');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-3', '-5');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-4', '-60');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-5', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-6', '-302');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-7', '-3660');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-8', '-32702');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-9', '-0.27152777777778');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-10', '-4.2715277777778');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-11', '-30.271527777778');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-12', '-1.0416666666667');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-13', '-6.1083333333333');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-14', '-391.27152777778');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-15', '-37.408333333333');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-ago-16', '-3.072553045859');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeInForcedUnitNotHumanized() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-1', '1');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-2', '5');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-3', '1');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-4', '302');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-5', '61');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-6', '32702');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-7', '1');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-8', '0.16666666666667');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-9', '1.0666666666667');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-10', '189');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-11', '13.233333333333');
    $this->assertElementContains('.test-extensions div.date-diff-unit-robot-in-12', '3.088295687885');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeAgoNotHumanized() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-1', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-2', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-3', '-5');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-4', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-5', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-6', '-5');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-7', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-8', '-9');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-9', '-6');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-10', '-4');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-11', '-30');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-12', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-13', '-5');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-14', '-1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-15', '-3');
    $this->assertElementContains('.test-extensions div.date-diff-robot-ago-16', '-3');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeInNotHumanized() {
    $this->drupalGet('/bamboo-twig-extensions');

    $this->assertElementContains('.test-extensions div.date-diff-robot-in-1', '1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-2', '5');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-3', '1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-4', '5');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-5', '1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-6', '9');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-7', '1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-8', '5');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-9', '1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-10', '6');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-11', '1');
    $this->assertElementContains('.test-extensions div.date-diff-robot-in-12', '3');
  }

}
