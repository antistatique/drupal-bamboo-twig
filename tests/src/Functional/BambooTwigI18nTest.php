<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests I18n twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_i18n
 */
class BambooTwigI18nTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'locale',
    'language',
    'node',
    'taxonomy',
    'bamboo_twig',
    'bamboo_twig_i18n',
    'bamboo_twig_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    $this->setUpLanguages();
    $this->setUpTags();
    $this->setUpArticles();
    $this->setUpTranslations();
  }

  /**
   * Sets up translations strings needed for test.
   */
  protected function setUpTranslations() {
    /** @var \Drupal\locale\StringStorageInterface $localStorage */
    $localStorage = $this->container->get('locale.storage');

    $thursday = $localStorage->createString([
      'source' => 'Thursday',
    ]);
    $thursday->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $thursday->lid,
      'language'    => 'fr',
      'translation' => 'Jeudi',
    ])->save();

    $thu = $localStorage->createString([
      'source' => 'Thu',
    ]);
    $thu->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $thu->lid,
      'language'    => 'fr',
      'translation' => 'Jeu',
    ])->save();

    $july = $localStorage->createString([
      'source' => 'July',
      'context' => 'Long month name',
    ]);
    $july->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $july->lid,
      'language'    => 'fr',
      'translation' => 'Juillet',
    ])->save();
  }

  /**
   * @covers Drupal\bamboo_twig_i18n\TwigExtension\I18n::getCurrentLanguage
   */
  public function testCurrentLang() {
    $this->drupalGet('/bamboo-twig-i18n');

    $this->assertElementContains('.test-i18n div.i18n-current-lang', 'en');

    $this->drupalGet('/fr/bamboo-twig-i18n');

    $this->assertElementContains('.test-i18n div.i18n-current-lang', 'fr');
  }

  /**
   * @covers Drupal\bamboo_twig_i18n\TwigExtension\I18n::formatDate
   */
  public function testFormatDate() {
    $this->drupalGet('/bamboo-twig-i18n');

    $this->assertElementContains('.test-i18n div.i18n-format-date-string', 'Thursday 24th July 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-timestamp', 'Thursday 24th July 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime', 'Thursday 24th July 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-datetimeplus', 'Thursday 24th July 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-drupaldatetime', 'Thursday 24th July 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime-medium', 'Thu, 07/24/2014');

    $this->drupalGet('/fr/bamboo-twig-i18n');

    $this->assertElementContains('.test-i18n div.i18n-format-date-string', 'Jeudi 24th Juillet 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-timestamp', 'Jeudi 24th Juillet 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime', 'Jeudi 24th Juillet 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-datetimeplus', 'Jeudi 24th Juillet 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-drupaldatetime', 'Jeudi 24th Juillet 2014');

    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime-medium', 'Jeu, 07/24/2014');
  }

  /**
   * @covers Drupal\bamboo_twig_i18n\TwigExtension\I18n::getTranslation
   */
  public function testGetTranslation() {
    $this->drupalGet('/bamboo-twig-i18n');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1-ru', 'News N°1');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1-en', 'News N°1');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1-de', 'News N°1');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1-fr', 'News N°1');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-2-en', 'News N°2');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1-de', 'News N°1');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-2-fr', 'Article N°2');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-3-en', 'News N°3');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-3-de', 'Artikel N°3');

    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-3-fr', 'Article N°3');

    $this->drupalGet('/bamboo-twig-i18n');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1', 'News N°1');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-2', 'News N°2');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-3', 'News N°3');

    $this->drupalGet('/fr/bamboo-twig-i18n');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1', 'News N°1');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-2', 'Article N°2');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-3', 'Article N°3');

    $this->drupalGet('/de/bamboo-twig-i18n');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-1', 'News N°1');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-2', 'News N°2');
    $this->assertElementContains('.test-i18n div.i18n-get-translation-node-3', 'Artikel N°3');
  }

  /**
   * @covers Drupal\bamboo_twig_i18n\TwigExtension\I18n::getTranslation
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntity
   */
  public function testGetTranslationReferencedField() {
    $this->drupalGet('/bamboo-twig-i18n');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-5', 'Tag N°5');

    $this->drupalGet('/fr/bamboo-twig-i18n');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-2', 'Mot clé N°2');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-3', 'Mot clé N°3');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-5', 'Mot clé N°5');

    $this->drupalGet('/de/bamboo-twig-i18n');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-3', 'Stichworte N°3');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertElementContains('.test-i18n div.loader-entity-reference-field-5', 'Tag N°5');
  }

}
