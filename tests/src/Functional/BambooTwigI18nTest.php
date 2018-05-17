<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\language\Entity\ConfigurableLanguage;

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
    'locale',
    'language',
    'node',
    'taxonomy',
    'bamboo_twig',
    'bamboo_twig_i18n',
    'bamboo_twig_test',
  ];

  /**
   * The articles Node used by this test.
   *
   * @var \Drupal\node\NodeInterface[]
   */
  protected $articles;

  /**
   * The tags Term used by this test.
   *
   * @var \Drupal\taxonomy\TermInterface[]
   */
  protected $tags;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    /** @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager */
    $this->entityTypeManager = $this->container->get('entity_type.manager');

    $this->setUpLanguages();
    $this->setUpArticles();
    $this->setUpTags();
    $this->setUpTranslations();
  }

  /**
   * Sets up languages needed for test.
   */
  protected function setUpLanguages() {
    // English (en) is created by default.
    ConfigurableLanguage::createFromLangcode('fr')->save();
    ConfigurableLanguage::createFromLangcode('de')->save();
  }

  /**
   * Setup default node for testing.
   */
  protected function setUpArticles() {
    // Create an article content type that we will use for testing.
    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);

    // Add default nodes.
    $this->articles = [];

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'  => 'article',
      'title' => 'News N°1',
    ]);
    $article->save();
    $this->articles[] = $article;

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'  => 'article',
      'title' => 'News N°2',
    ]);
    $article->save();
    $article_translation = $article->addTranslation('fr', $article->toArray());
    $article_translation->title = 'Article N°2';
    $article_translation->save();
    $this->articles[] = $article;

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'  => 'article',
      'title' => 'News N°3',
    ]);
    $article->save();

    $article_translation = $article->addTranslation('fr', $article->toArray());
    $article_translation->title = 'Article N°3';
    $article_translation->save();

    $article_translation = $article->addTranslation('de', $article->toArray());
    $article_translation->title = 'Artikel N°3';
    $article_translation->save();

    $this->articles[] = $article;
  }

  /**
   * Setup default taxonomy vocabulary with terms for testing.
   */
  protected function setUpTags() {
    // Create a taxonomy vocabulary that we will use for testing.
    $this->entityTypeManager->getStorage('taxonomy_vocabulary')->create([
      'vid'  => 'tags',
      'name' => 'Tags',
    ])->save();

    // Add tests tags.
    $this->tags = [];
    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name' => 'Tags N°1',
      'vid'  => 'tags',
    ]);
    $tag->save();
    $this->tags[] = $tag;

    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name' => 'Tags N°2',
      'vid'  => 'tags',
    ]);
    $tag->save();

    $tag_translation = $tag->addTranslation('fr', $tag->toArray());
    $tag_translation->title = 'Mot clé N°2';
    $tag_translation->save();

    $this->tags[] = $tag;

    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name' => 'Tags N°3',
      'vid'  => 'tags',
    ]);
    $tag->save();

    $tag_translation = $tag->addTranslation('fr', $tag->toArray());
    $tag_translation->title = 'Mot clé N°3';
    $tag_translation->save();

    $tag_translation = $tag->addTranslation('de', $tag->toArray());
    $tag_translation->title = 'Stichworte N°3';
    $tag_translation->save();

    $this->tags[] = $tag;
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

    $this->assertElementPresent('.test-i18n div.i18n-current-lang');
    $this->assertElementContains('.test-i18n div.i18n-current-lang', 'en');

    $this->drupalGet('/fr/bamboo-twig-i18n');

    $this->assertElementPresent('.test-i18n div.i18n-current-lang');
    $this->assertElementContains('.test-i18n div.i18n-current-lang', 'fr');
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

    $this->drupalGet('/fr/bamboo-twig-i18n');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-string');
    $this->assertElementContains('.test-i18n div.i18n-format-date-string', 'Jeudi 24th Juillet 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-timestamp');
    $this->assertElementContains('.test-i18n div.i18n-format-date-timestamp', 'Jeudi 24th Juillet 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-datetime');
    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime', 'Jeudi 24th Juillet 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-datetimeplus');
    $this->assertElementContains('.test-i18n div.i18n-format-date-datetimeplus', 'Jeudi 24th Juillet 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-drupaldatetime');
    $this->assertElementContains('.test-i18n div.i18n-format-date-drupaldatetime', 'Jeudi 24th Juillet 2014');

    $this->assertElementPresent('.test-i18n div.i18n-format-date-datetime-medium');
    $this->assertElementContains('.test-i18n div.i18n-format-date-datetime-medium', 'Jeu, 07/24/2014');
  }

}
