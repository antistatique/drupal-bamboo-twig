<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
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
  protected $defaultTheme = 'stark';

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
   * | Nid | Title    | EN           | DE | FR           |
   * |-----|----------|--------------|----|--------------|
   * |   1 | News N°1 | X (original) |    |              |
   * |   2 | News N°2 | X (original) |    |       X      |
   * |   3 | News N°3 | X (original) |  X |       X      |
   * |   4 | News N°4 |              |    | X (original) |
   * |   5 | News N°5 |       X      |    | X (original) |
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
  }

  /**
   * Setup default taxonomy vocabulary with terms for testing.
   *
   * Summary:
   * | Tid | Name    | EN           | DE | FR           |
   * |-----|---------|--------------|----| -------------|
   * |   1 | Tag N°1 | X (original) |    |              |
   * |   2 | Tag N°2 | X (original) |    |       X      |
   * |   3 | Tag N°3 | X (original) |  X |       X      |
   * |   4 | Tag N°4 |              |    | X (original) |
   * |   5 | Tag N°5 |       X      |    | X (original) |
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
  }

  /**
   * Enables Twig debugging.
   */
  protected function debugOn() {
    // Enable debug, rebuild the service container, and clear all caches.
    $parameters = $this->container->getParameter('twig.config');
    if (!$parameters['debug']) {
      $parameters['debug'] = TRUE;
      $this->setContainerParameter('twig.config', $parameters);
      $this->rebuildContainer();
      $this->resetAll();
    }
  }

  /**
   * Disables Twig debugging.
   */
  protected function debugOff() {
    // Disable debug, rebuild the service container, and clear all caches.
    $parameters = $this->container->getParameter('twig.config');
    if ($parameters['debug']) {
      $parameters['debug'] = FALSE;
      $this->setContainerParameter('twig.config', $parameters);
      $this->rebuildContainer();
      $this->resetAll();
    }
  }

  /**
   * Asserts that the element with the given CSS selector is present.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   *
   * @throws Behat\Mink\Exception\ElementHtmlException
   *   When the condition is not fulfilled.
   *
   * @see \Behat\Mink\WebAssert::elementExists
   */
  public function assertElementPresent($css_selector) {
    $this->assertSession()->elementExists('css', $css_selector);
  }

  /**
   * Asserts that the element with the given CSS selector is present.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   * @param string $html
   *   Expected text.
   *
   * @throws Behat\Mink\Exception\ElementHtmlException
   *   When the condition is not fulfilled.
   *
   * @see \Behat\Mink\WebAssert::elementContains
   */
  public function assertElementContains($css_selector, $html) {
    $this->assertSession()->elementContains('css', $css_selector, $html);
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
   * Passes if a link with the specified label is found.
   *
   * An optional link index may be passed.
   *
   * @param string $label
   *   Text between the anchor tags.
   * @param int $index
   *   (optional) Link position counting from zero.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   *   Thrown when element doesn't exist, or the link label is a different one.
   *
   * @see \Behat\Mink\WebAssert::linkExists
   */
  public function assertLinkLabelExist($label, $index = 0) {
    $this->assertSession()->linkExists($label, $index = 0);
  }

  /**
   * Passes if a link containing a given href (part) is found.
   *
   * @param string $href
   *   The full or partial value of the 'href' attribute of the anchor tag.
   * @param int $index
   *   (optional) Link position counting from zero.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   *   Thrown when element doesn't exist, or the link label is a different one.
   *
   * @see \Behat\Mink\WebAssert::linkByHrefExists
   */
  public function assertLinkUrlExist($href, $index = 0) {
    $this->assertSession()->linkByHrefExists($href, $index = 0);
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
