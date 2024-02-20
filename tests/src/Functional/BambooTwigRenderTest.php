<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\FileInterface;
use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;

/**
 * Tests Renders twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_render
 */
class BambooTwigRenderTest extends BambooTwigTestBase {
  use TaxonomyTestTrait;

  /**
   * A user with administration access.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * A file used by this test.
   *
   * @var \Drupal\file\FileInterface
   */
  protected $file;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'locale',
    'language',
    'node',
    'user',
    'taxonomy',
    'image',
    'file',
    'system',
    'views',
    'block_test',
    'bamboo_twig',
    'bamboo_twig_loader',
    'bamboo_twig_test',
  ];

  /**
   * Permissions for the admin user that will be logged-in for test.
   *
   * @var array
   */
  protected static $adminUserPermissions = [
    'access content',
    'administer blocks',
    'administer site configuration',
    'view the administration theme',
    'administer menu',
    'access administration pages',
    // Node module permissions.
    'access content overview',
    'administer content types',
    'administer nodes',
    'bypass node access',
    // Taxonomy module permissions.
    'administer taxonomy',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    $this->setUpLanguages();
    $this->setUpTags();
    $this->setUpArticles();
    $this->setUpPages();

    // Create a user for tests.
    $this->adminUser = $this->drupalCreateUser(self::$adminUserPermissions);

    $this->adminUser->set('name', 'john.doe');
    $this->adminUser->save();

    // Create a file for tests.
    $this->file = $this->createFile();

    $this->container->get('router.builder')->rebuild();
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderBlock
   */
  public function testBlock() {
    $this->drupalGet('/bamboo-twig-render');

    // Tests for Block Plugin.
    $this->assertSession()->elementExists('css', '.test-render div.render-block-plugin');
    $this->assertSession()->elementContains('css', '.test-render div.render-block-plugin', '<span>Powered by <a href="https://www.drupal.org">Drupal</a></span>');

    // Tests for Block Entity.
    $this->assertSession()->elementExists('css', '.test-render div.render-block-entity');
    $this->assertSession()->elementExists('css', '.test-render div.render-block-entity #block-stark-branding');

    // Tests for Block Plugin with context.
    $this->assertSession()->elementExists('css', '.test-render .render-block-plugin-context #test_context_aware--username');
    $this->assertElementEmpty('.test-render .render-block-plugin-context #test_context_aware--username');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderBlock
   */
  public function testBlockLoggedIn() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('/bamboo-twig-render');

    // Tests for Block Plugin with context.
    $this->assertSession()->elementExists('css', '.test-render .render-block-plugin-context #test_context_aware--username');
    $this->assertSession()->elementContains('css', '.test-render .render-block-plugin-context #test_context_aware--username', 'john.doe');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderRegion
   */
  public function testRegion() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-region');
    $this->assertSession()->elementExists('css', '.test-render div.render-region #block-bamboo-twig-theme-test-branding');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-render');

    // Asserts display mode are rendered properly.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1 h2 a', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1 article', 'Submitted by');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1-teaser h2 a', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1-teaser article', 'Submitted by');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1-teaser .links', 'Read more');

    // Asserts node translations works.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-2 h2 a', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-2-teaser h2 a', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3 h2 a', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3-teaser h2 a', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4 h2 a', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4-teaser h2 a', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5 h2 a', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5-teaser h2 a', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-6 h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-6-teaser h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-7 h2 a', 'Page N°7');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-7-teaser h2 a', 'Page N°7');
    $this->assertElementEmpty('.test-render div.render-entity-node-8');
    $this->assertElementEmpty('.test-render div.render-entity-node-8-teaser');

    // Asserts display mode are rendered properly.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-1 h2 a', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-1-link h2 a', 'Tag N°1');

    // Asserts taxonomy translations works.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-2 h2 a', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-2-link h2 a', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-3 h2 a', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-3-link h2 a', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-4 h2 a', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-4-link h2 a', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-5 h2 a', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-5-link h2 a', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-6 h2 a', 'Tag N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-6-link h2 a', 'Tag N°6');
    $this->assertElementEmpty('.test-render div.render-entity-taxonomy-term-7');
    $this->assertElementEmpty('.test-render div.render-entity-taxonomy-term-7-link');

    // Entity user full loaded.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-user', 'Member for');
    // Entity user compact loaded.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-user-compact', 'Member for');

    $this->drupalGet('/fr/bamboo-twig-render');

    // Asserts node translations works.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1 h2 a', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1-teaser h2 a', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-2 h2 a', 'Article N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-2-teaser h2 a', 'Article N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3 h2 a', 'Article N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3-teaser h2 a', 'Article N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4 h2 a', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4-teaser h2 a', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5 h2 a', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5-teaser h2 a', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-6 h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-6-teaser h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-7 h2 a', 'Page N°7');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-7-teaser h2 a', 'Page N°7');
    $this->assertElementEmpty('.test-render div.render-entity-node-8');
    $this->assertElementEmpty('.test-render div.render-entity-node-8-teaser');

    // Asserts taxonomy translations works.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-1 h2 a', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-1-link h2 a', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-2 h2 a', 'Mot clé N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-2-link h2 a', 'Mot clé N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-3 h2 a', 'Mot clé N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-3-link h2 a', 'Mot clé N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-4 h2 a', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-4-link h2 a', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-5 h2 a', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-5-link h2 a', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-6 h2 a', 'Tag N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-6-link h2 a', 'Tag N°6');
    $this->assertElementEmpty('.test-render div.render-entity-taxonomy-term-7');
    $this->assertElementEmpty('.test-render div.render-entity-taxonomy-term-7-link');

    $this->drupalGet('/de/bamboo-twig-render');

    // Asserts node translations works.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1 h2 a', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-1-teaser h2 a', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-2 h2 a', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-2-teaser h2 a', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3 h2 a', 'Artikel N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3', 'Stichworte N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-3-teaser h2 a', 'Artikel N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4 h2 a', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-4-teaser h2 a', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5 h2 a', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-5-teaser h2 a', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-6 h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-6-teaser h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-7 h2 a', 'Page N°7');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-node-7-teaser h2 a', 'Page N°7');
    $this->assertElementEmpty('.test-render div.render-entity-node-8');
    $this->assertElementEmpty('.test-render div.render-entity-node-8-teaser');

    // Asserts taxonomy translations works.
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-1 h2 a', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-1-link h2 a', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-2 h2 a', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-2-link h2 a', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-3 h2 a', 'Stichworte N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-3-link h2 a', 'Stichworte N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-4 h2 a', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-4-link h2 a', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-5 h2 a', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-5-link h2 a', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-6 h2 a', 'Tag N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-entity-taxonomy-term-6-link h2 a', 'Tag N°6');
    $this->assertElementEmpty('.test-render div.render-entity-taxonomy-term-7');
    $this->assertElementEmpty('.test-render div.render-entity-taxonomy-term-7-link');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderEntityRevision
   */
  public function testRenderEntityRevision() {
    $this->drupalGet('/bamboo-twig-render-revision');

    // Load entity article (node) revision.
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-5 h2 a', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-5-teaser h2 a', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-6 h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-6-teaser h2 a', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-7 h2 a', 'Revised News N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-7-teaser h2 a', 'Revised News N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-8 h2 a', 'Page N°7');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-8-teaser h2 a', 'Page N°7');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-9 h2 a', 'Revised Page N°7');
    $this->assertSession()->elementContains('css', '.test-render-revision .render-entity-node-revision-9-teaser h2 a', 'Revised Page N°7');
    $this->assertElementEmpty('.test-render-revision .render-entity-node-revision-10');
    $this->assertElementEmpty('.test-render-revision .render-entity-node-revision-10-teaser');

    // Load entity tag (taxonomy) revision.
    $this->assertSession()->elementContains('css', '.test-render-revision div.render-entity-taxonomy-term-revision-5 h2 a', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render-revision div.render-entity-taxonomy-term-revision-5-link h2 a', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render-revision div.render-entity-taxonomy-term-revision-6 h2 a', 'Tag N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision div.render-entity-taxonomy-term-revision-6-link h2 a', 'Tag N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision div.render-entity-taxonomy-term-revision-7 h2 a', 'Revised Tag N°6');
    $this->assertSession()->elementContains('css', '.test-render-revision div.render-entity-taxonomy-term-revision-7-link h2 a', 'Revised Tag N°6');
    $this->assertElementEmpty('.test-render-revision div.render-entity-taxonomy-term-revision-8');
    $this->assertElementEmpty('.test-render-revision div.render-entity-taxonomy-term-revision-8-link');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderImage
   */
  public function testImage() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-image');
    $this->assertSession()->elementExists('css', '.test-render div.render-image img');
    $this->assertSession()->elementExists('css', '.test-render div.render-image-alt img[alt="Quam litora posuere"]');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderImageStyle
   */
  public function testImageStyle() {
    $this->drupalGet('/bamboo-twig-render');

    $this->assertSession()->elementExists('css', '.test-render div.render-image-style-uri');
    $this->assertSession()->elementContains('css', '.test-render div.render-image-style-uri', 'files/styles/thumbnail/public/antistatique.jpg');

    $this->assertSession()->elementExists('css', '.test-render div.render-image-style-uri-preprocess');
    $this->assertSession()->elementContains('css', '.test-render div.render-image-style-uri-preprocess', 'files/styles/thumbnail/public/antistatique.jpg');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderField
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-render');

    // Entity articles (nodes) title field.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-3', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-5', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-render div.render-field-title-node-8');

    // Entity tags (taxonomy) name field.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-render div.render-field-taxonomy-term-7');

    // Entity articles (node) tags reference field.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-5', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-6', 'Tag N°5');
    $this->assertElementEmpty('.test-render div.render-field-reference-node-7');

    // Entity file uri.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-file', $this->file->filename->value);

    // Entity user username.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-user', 'admin');

    $this->drupalGet('/fr/bamboo-twig-render');

    // Entity articles (nodes) title field - French.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-3', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-5', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-render div.render-field-title-node-8');

    // Entity tags (taxonomy) name field - French.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-render div.render-field-taxonomy-term-7');

    // Entity articles (node) tags reference field - French.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-2', 'Mot clé N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-3', 'Mot clé N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-6', 'Mot clé N°5');
    $this->assertElementEmpty('.test-render div.render-field-reference-node-7');

    $this->drupalGet('/de/bamboo-twig-render');

    // Entity articles (nodes) title field - German.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-3', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-5', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-title-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-render div.render-field-title-node-8');

    // Entity tags (taxonomy) name field - German.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-render div.render-field-taxonomy-term-7');

    // Entity articles (node) tags reference field - German.
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-3', 'Stichworte N°3');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-5', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-render div.render-field-reference-node-6', 'Tag N°5');
    $this->assertElementEmpty('.test-render div.render-field-reference-node-7');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderField
   */
  public function testFieldOnEntityFromRoutes() {
    // Accessing unpublished revision page required to be authenticated.
    $this->drupalLogin($this->adminUser);

    // Load entity page (node) from the current route.
    $this->drupalGet('/node/7');
    $this->assertSession()->elementContains('css', '.render-field-title-node', 'Page N°7');

    // Load entity page (node revision) from the current route.
    $this->drupalGet('/node/7/revisions/9/view');
    $this->assertSession()->elementContains('css', '.render-field-title-node', 'Page N°7');

    // Load entity article (node) from the current route.
    $this->drupalGet('/node/6');
    $this->assertSession()->elementContains('css', '.render-field-title-node', 'News N°6');
    $this->assertSession()->elementContains('css', '.render-field-reference-node', 'Tag N°5');

    // Load entity article (node revision) from the current route.
    $this->drupalGet('/node/6/revisions/7/view');
    $this->assertSession()->elementContains('css', '.render-field-title-node', 'News N°6');
    $this->assertSession()->elementContains('css', '.render-field-reference-node', 'Tag N°5');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderMenu
   */
  public function testMenu() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-menu-no-access');
    $this->assertSession()->elementNotExists('css', '.test-render div.render-menu-no-access ul');
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-menu-all');
    $this->assertElementCount('ul', 9, '.test-render div.render-menu-all');

    // Since Drupal 10.2.x the default Drupal distribution have X menu items.
    if (version_compare(\Drupal::VERSION, '10.2', '>=')) {
      $this->assertElementCount('li', 24, '.test-render div.render-menu-all');
    }
    // Since Drupal 10.1.x the default Drupal distribution have X menu items.
    elseif (version_compare(\Drupal::VERSION, '10.1', '>=')) {
      $this->assertElementCount('li', 29, '.test-render div.render-menu-all');
    }
    else {
      $this->assertElementCount('li', 27, '.test-render div.render-menu-all');
    }

    $this->assertSession()->elementExists('css', '.test-render div.render-menu-level');
    $this->assertElementCount('ul', 8, '.test-render div.render-menu-level');

    // Since Drupal 10.2.x the default Drupal distribution have X menu items.
    if (version_compare(\Drupal::VERSION, '10.2', '>=')) {
      $this->assertElementCount('li', 23, '.test-render div.render-menu-level');
    }
    // Since Drupal 10.1.x the default Drupal distribution have X menu items.
    elseif (version_compare(\Drupal::VERSION, '10.1', '>=')) {
      $this->assertElementCount('li', 28, '.test-render div.render-menu-level');
    }
    else {
      $this->assertElementCount('li', 26, '.test-render div.render-menu-level');
    }

    $this->assertSession()->elementExists('css', '.test-render div.render-menu-depth');
    $this->assertElementCount('ul', 2, '.test-render div.render-menu-depth');
    $this->assertElementCount('li', 4, '.test-render div.render-menu-depth');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderForm
   */
  public function testForm() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-form');
    $this->assertSession()->elementExists('css', '.test-render div.render-form form.system-cron-settings');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::getFunctions
   */
  public function testViews() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-views');
    $this->assertSession()->elementExists('css', '.test-render div.render-views .views-element-container');
  }

  /**
   * Creates and gets test image file.
   *
   * @return \Drupal\file\FileInterface
   *   File object.
   */
  protected function createFile() {
    /** @var \Drupal\Component\PhpStorage\FileStorage $file_storage */
    $file_storage = $this->container->get('entity_type.manager')->getStorage('file');
    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = $this->container->get('file_system');

    $file_system->copy(\Drupal::service('extension.list.module')->getPath('bamboo_twig_test') . '/files/antistatique.jpg', PublicStream::basePath());

    $file = $file_storage->create([
      'uri' => 'public://antistatique.jpg',
      'status' => FileInterface::STATUS_PERMANENT,
    ]);
    $file->save();

    return $file;
  }

}
