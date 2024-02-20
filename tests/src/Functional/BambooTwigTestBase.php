<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\field\Traits\EntityReferenceTestTrait;

/**
 * Has some additional helper methods to make test code more readable.
 */
abstract class BambooTwigTestBase extends BrowserTestBase {
  use EntityReferenceTestTrait;

  /**
   * The Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The articles Node used by this test.
   *
   * @var \Drupal\node\NodeInterface[]
   */
  protected $articles;

  /**
   * The pages Node used by this test.
   *
   * @var \Drupal\node\NodeInterface[]
   */
  protected $pages;

  /**
   * The tags Term used by this test.
   *
   * @var \Drupal\taxonomy\TermInterface[]
   */
  protected $tags;

  /**
   * We use the minimal profile because we want to test local actions.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'bamboo_twig_theme_test';

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    /** @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager */
    $this->entityTypeManager = $this->container->get('entity_type.manager');
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
   * Setup default articles node for testing.
   *
   * Summary:
   * | Nid | Title    | EN           | DE | FR           | Has revision ? |
   * |-----|----------|--------------|----|--------------|                |
   * |   7 | Page N°17| X (original) |    |              |        x       |
   */
  protected function setUpPages() {
    // Create a page content type that we will use for testing.
    $this->drupalCreateContentType(['type' => 'page', 'name' => 'Page']);

    // Create a page Node in English (original language) with a revision.
    $page = $this->entityTypeManager->getStorage('node')->create([
      'nid' => 7,
      'type' => 'page',
      'title' => 'Page N°7',
    ]);
    $page->save();
    $this->pages[] = $page;

    // Make this change a new revision.
    $page->setNewRevision();
    $page->set('title', 'Revised Page N°7');
    $page->revision_log = 'Created revision for Node 7';
    $page->isDefaultRevision(FALSE);
    $page->setRevisionUserId(1);
    $page->save();
  }

  /**
   * Setup default articles node for testing.
   *
   * Summary:
   * | Nid | Title    | EN           | DE | FR           | Has revision ? |
   * |-----|----------|--------------|----|--------------|                |
   * |   1 | News N°1 | X (original) |    |              |                |
   * |   2 | News N°2 | X (original) |    |       X      |                |
   * |   3 | News N°3 | X (original) |  X |       X      |                |
   * |   4 | News N°4 |              |    | X (original) |                |
   * |   5 | News N°5 |       X      |    | X (original) |                |
   * |   6 | News N°6 | X (original) |    |              |       X        |
   */
  protected function setUpArticles() {
    // Create an article content type that we will use for testing.
    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);

    // Create a reference field for tag(s) on article.
    $this->createEntityReferenceField(
      'node',
      'article',
      'field_tags',
      NULL,
      'taxonomy_term',
      'default',
      ['target_bundles' => ['tags' => 'tags']],
      FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED
    );

    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository */
    $entity_display_repository = $this->container->get('entity_display.repository');

    // Show on default display and teaser.
    $entity_display_repository->getViewDisplay('node', 'article', 'default')
      ->setComponent('field_tags', [
        'type' => 'entity_reference_label',
      ])
      ->save();

    // Add default nodes.
    $this->articles = [];

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'  => 'article',
      'title' => 'News N°1',
      'field_tags' => $this->tags[3],
    ]);
    $article->save();
    $this->articles[] = $article;

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'       => 'article',
      'title'      => 'News N°2',
      'field_tags' => $this->tags[1],
    ]);
    $article->save();
    $article_translation = $article->addTranslation('fr', $article->toArray());
    $article_translation->title = 'Article N°2';
    $article_translation->save();
    $this->articles[] = $article;

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'       => 'article',
      'title'      => 'News N°3',
      'field_tags' => $this->tags[2],
    ]);
    $article->save();
    $article_translation = $article->addTranslation('fr', $article->toArray());
    $article_translation->title = 'Article N°3';
    $article_translation->save();
    $article_translation = $article->addTranslation('de', $article->toArray());
    $article_translation->title = 'Artikel N°3';
    $article_translation->save();
    $this->articles[] = $article;

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'       => 'article',
      'title'      => 'Article N°4',
      'langcode'   => 'fr',
      'field_tags' => $this->tags[0],
    ]);
    $article->save();
    $this->articles[] = $article;

    $article = $this->entityTypeManager->getStorage('node')->create([
      'type'     => 'article',
      'title'    => 'Article N°5',
      'langcode' => 'fr',
      'field_tags' => $this->tags[4],
    ]);
    $article->save();
    $article_translation = $article->addTranslation('en', $article->toArray());
    $article_translation->title = 'News N°5';
    $article_translation->save();
    $this->articles[] = $article;

    // Create an article Node in English (original language) with a revision.
    $article = $this->entityTypeManager->getStorage('node')->create([
      'type' => 'article',
      'title' => 'News N°6',
      'field_tags' => $this->tags[4],
    ]);
    $article->save();
    $this->articles[] = $article;

    // Make this change a new revision.
    $article->setNewRevision();
    $article->set('title', 'Revised News N°6');
    $article->revision_log = 'Created revision for Node 6';
    $article->isDefaultRevision(FALSE);
    $article->setRevisionUserId(1);
    $article->save();
  }

  /**
   * Setup default taxonomy vocabulary with terms for testing.
   *
   * Summary:
   * | Tid | Name    | EN           | DE | FR           | Has revision ? |
   * |-----|---------|--------------|----| -------------|                |
   * |   1 | Tag N°1 | X (original) |    |              |                |
   * |   2 | Tag N°2 | X (original) |    |       X      |                |
   * |   3 | Tag N°3 | X (original) |  X |       X      |                |
   * |   4 | Tag N°4 |              |    | X (original) |                |
   * |   5 | Tag N°5 |       X      |    | X (original) |                |
   * |   6 | Tag N°6 | X (original) |    |              |       X        |
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
      'name' => 'Tag N°1',
      'vid'  => 'tags',
    ]);
    $tag->save();
    $this->tags[] = $tag;

    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name' => 'Tag N°2',
      'vid'  => 'tags',
    ]);
    $tag->save();
    $tag_translation = $tag->addTranslation('fr', $tag->toArray());
    $tag_translation->name = 'Mot clé N°2';
    $tag_translation->save();
    $this->tags[] = $tag;

    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name' => 'Tag N°3',
      'vid'  => 'tags',
    ]);
    $tag->save();
    $tag_translation = $tag->addTranslation('fr', $tag->toArray());
    $tag_translation->name = 'Mot clé N°3';
    $tag_translation->save();
    $tag_translation = $tag->addTranslation('de', $tag->toArray());
    $tag_translation->name = 'Stichworte N°3';
    $tag_translation->save();
    $this->tags[] = $tag;

    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name'     => 'Mot clé N°4',
      'vid'      => 'tags',
      'langcode' => 'fr',
    ]);
    $tag->save();
    $this->tags[] = $tag;

    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'name'     => 'Mot clé N°5',
      'vid'      => 'tags',
      'langcode' => 'fr',
    ]);
    $tag->save();
    $tag_translation = $tag->addTranslation('en', $tag->toArray());
    $tag_translation->name = 'Tag N°5';
    $tag_translation->save();
    $this->tags[] = $tag;

    // Create a Tag term in English (original language) with a revision.
    $tag = $this->entityTypeManager->getStorage('taxonomy_term')->create([
      'vid' => 'tags',
      'name' => 'Tag N°6',
    ]);
    $tag->save();
    // Make this change a new revision.
    $tag->setNewRevision();
    $tag->set('name', 'Revised Tag N°6');
    $tag->revision_log = 'Created revision for Term 6';
    $tag->isDefaultRevision(FALSE);
    $tag->setRevisionUserId(1);
    $tag->save();
    $this->tags[] = $tag;
  }

  /**
   * Asserts that the element with the given CSS selector is empty.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   *
   * @throws Behat\Mink\Exception\ElementHtmlException
   *   When the condition is not fulfilled.
   */
  public function assertElementEmpty($css_selector) {
    $element = $this->assertSession()->elementExists('css', $css_selector);
    $actual = trim($element->getHtml());

    $message = sprintf(
      'The element "%s" was not empty, but it should not be.',
      $css_selector
    );

    $this->assertTrue(empty($actual), $message);
  }

  /**
   * Asserts that the element with the given CSS selector is present.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   * @param int $count
   *   Expected count.
   * @param string $locator
   *   Container that must have $css_selector.
   *
   * @throws Behat\Mink\Exception\ExpectationException
   *   When the condition is not fulfilled.
   *
   * @see \Behat\Mink\WebAssert::elementsCount
   */
  public function assertElementCount($css_selector, $count, $locator = NULL) {
    $container = $locator ? $this->getSession()->getPage()->find('css', $locator) : NULL;
    $this->assertSession()->elementsCount('css', $css_selector, $count, $container);
  }

}
