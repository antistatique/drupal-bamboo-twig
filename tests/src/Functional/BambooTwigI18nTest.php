<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests I18n twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_i18n
 */
class BambooTwigI18nTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_i18n',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_i18n\TwigExtension\I18n::getCurrentLanguage
   */
  public function testCurrentLang() {
    $this->drupalGet('/bamboo-twig-i18n');

    $this->assertElementPresent('.test-i18n div.i18n-current-lang');
    $this->assertElementContains('.test-i18n div.i18n-current-lang', 'en');
  }

  /**
   * @covers Drupal\bamboo_twig_i18n\TwigExtension\I18n::formatDate
   */
  public function testFormatDate() {
    $this->drupalGet('/bamboo-twig-i18n');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-string');
    $this->assertElementContains('.test-i18n div.i18n-format-date-string', 'Thursday 24th July 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-timestamp');
    $this->assertElementContains('.test-i18n div.i18n-format-date-timestamp', 'Thursday 24th July 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-datetime');
    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime', 'Thursday 24th July 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-datetimeplus');
    $this->assertElementContains('.test-i18n div.i18n-format-date-datetimeplus', 'Thursday 24th July 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-drupaldatetime');
    $this->assertElementContains('.test-i18n div.i18n-format-date-drupaldatetime', 'Thursday 24th July 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-datetime-medium');
    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime-medium', 'Thu, 07/24/2014');
  }

}
