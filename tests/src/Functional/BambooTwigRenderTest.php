<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\FileInterface;

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
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    $this->setUpLanguages();
    $this->setUpTags();
    $this->setUpArticles();

    // Create a user for tests.
    $this->admin_user = $this->drupalCreateUser([
      'access content',
      'administer blocks',
      'administer content types',
      'bypass node access',
      'administer site configuration',
      'view the administration theme',
      'administer menu',
      'access administration pages',
    ]);

    $this->admin_user->set('name', 'john.doe');
    $this->admin_user->save();

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
    $this->assertElementContains('.test-render div.render-block-plugin', '<span>Powered by <a href="https://www.drupal.org">Drupal</a></span>');

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
    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-render');

    // Tests for Block Plugin with context.
    $this->assertSession()->elementExists('css', '.test-render .render-block-plugin-context #test_context_aware--username');
    $this->assertElementContains('.test-render .render-block-plugin-context #test_context_aware--username', 'john.doe');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderRegion
   */
  public function testRegion() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-region');
    $this->assertSession()->elementExists('css', '.test-render div.render-region #block-stark-branding');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-render');

    // Asserts display mode are rendered properly.
    $this->assertElementContains('.test-render div.render-entity-node-1 h2 a', 'News N°1');
    $this->assertElementContains('.test-render div.render-entity-node-1 footer', 'Submitted by');
    $this->assertElementContains('.test-render div.render-entity-node-1', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-node-1-teaser h2 a', 'News N°1');
    $this->assertElementContains('.test-render div.render-entity-node-1-teaser footer', 'Submitted by');
    $this->assertElementContains('.test-render div.render-entity-node-1-teaser .links', 'Read more');

    // Asserts node translations works.
    $this->assertElementContains('.test-render div.render-entity-node-2 h2 a', 'News N°2');
    $this->assertElementContains('.test-render div.render-entity-node-2', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-entity-node-2-teaser h2 a', 'News N°2');
    $this->assertElementContains('.test-render div.render-entity-node-3 h2 a', 'News N°3');
    $this->assertElementContains('.test-render div.render-entity-node-3', 'Tag N°3');
    $this->assertElementContains('.test-render div.render-entity-node-3-teaser h2 a', 'News N°3');
    $this->assertElementContains('.test-render div.render-entity-node-4 h2 a', 'Article N°4');
    $this->assertElementContains('.test-render div.render-entity-node-4', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-node-4-teaser h2 a', 'Article N°4');
    $this->assertElementContains('.test-render div.render-entity-node-5 h2 a', 'News N°5');
    $this->assertElementContains('.test-render div.render-entity-node-5', 'Tag N°5');
    $this->assertElementContains('.test-render div.render-entity-node-5-teaser h2 a', 'News N°5');

    // Asserts display mode are rendered properly.
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-1 h2 a', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-1-link h2 a', 'Tag N°1');

    // Asserts taxonomy translations works.
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-2 h2 a', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-2-link h2 a', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-3 h2 a', 'Tag N°3');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-3-link h2 a', 'Tag N°3');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-4 h2 a', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-4-link h2 a', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-5 h2 a', 'Tag N°5');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-5-link h2 a', 'Tag N°5');

    // Entity user full loaded.
    $this->assertElementContains('.test-render div.render-entity-user', 'Member for');
    // Entity user compact loaded.
    $this->assertElementContains('.test-render div.render-entity-user-compact', 'Member for');

    $this->drupalGet('/fr/bamboo-twig-render');

    // Asserts node translations works.
    $this->assertElementContains('.test-render div.render-entity-node-1 h2 a', 'News N°1');
    $this->assertElementContains('.test-render div.render-entity-node-1', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-node-1-teaser h2 a', 'News N°1');
    $this->assertElementContains('.test-render div.render-entity-node-2 h2 a', 'Article N°2');
    $this->assertElementContains('.test-render div.render-entity-node-2', 'Mot clé N°2');
    $this->assertElementContains('.test-render div.render-entity-node-2-teaser h2 a', 'Article N°2');
    $this->assertElementContains('.test-render div.render-entity-node-3 h2 a', 'Article N°3');
    $this->assertElementContains('.test-render div.render-entity-node-3', 'Mot clé N°3');
    $this->assertElementContains('.test-render div.render-entity-node-3-teaser h2 a', 'Article N°3');
    $this->assertElementContains('.test-render div.render-entity-node-4 h2 a', 'Article N°4');
    $this->assertElementContains('.test-render div.render-entity-node-4', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-node-4-teaser h2 a', 'Article N°4');
    $this->assertElementContains('.test-render div.render-entity-node-5 h2 a', 'Article N°5');
    $this->assertElementContains('.test-render div.render-entity-node-5', 'Mot clé N°5');
    $this->assertElementContains('.test-render div.render-entity-node-5-teaser h2 a', 'Article N°5');

    // Asserts taxonomy translations works.
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-1 h2 a', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-1-link h2 a', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-2 h2 a', 'Mot clé N°2');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-2-link h2 a', 'Mot clé N°2');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-3 h2 a', 'Mot clé N°3');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-3-link h2 a', 'Mot clé N°3');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-4 h2 a', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-4-link h2 a', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-5 h2 a', 'Mot clé N°5');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-5-link h2 a', 'Mot clé N°5');

    $this->drupalGet('/de/bamboo-twig-render');

    // Asserts node translations works.
    $this->assertElementContains('.test-render div.render-entity-node-1 h2 a', 'News N°1');
    $this->assertElementContains('.test-render div.render-entity-node-1', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-node-1-teaser h2 a', 'News N°1');
    $this->assertElementContains('.test-render div.render-entity-node-2 h2 a', 'News N°2');
    $this->assertElementContains('.test-render div.render-entity-node-2', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-entity-node-2-teaser h2 a', 'News N°2');
    $this->assertElementContains('.test-render div.render-entity-node-3 h2 a', 'Artikel N°3');
    $this->assertElementContains('.test-render div.render-entity-node-3', 'Stichworte N°3');
    $this->assertElementContains('.test-render div.render-entity-node-3-teaser h2 a', 'Artikel N°3');
    $this->assertElementContains('.test-render div.render-entity-node-4 h2 a', 'Article N°4');
    $this->assertElementContains('.test-render div.render-entity-node-4', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-node-4-teaser h2 a', 'Article N°4');
    $this->assertElementContains('.test-render div.render-entity-node-5 h2 a', 'News N°5');
    $this->assertElementContains('.test-render div.render-entity-node-5', 'Tag N°5');
    $this->assertElementContains('.test-render div.render-entity-node-5-teaser h2 a', 'News N°5');

    // Asserts taxonomy translations works.
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-1 h2 a', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-1-link h2 a', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-2 h2 a', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-2-link h2 a', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-3 h2 a', 'Stichworte N°3');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-3-link h2 a', 'Stichworte N°3');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-4 h2 a', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-4-link h2 a', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-5 h2 a', 'Tag N°5');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-5-link h2 a', 'Tag N°5');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderImage
   */
  public function testImage() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-image');
    $this->assertSession()->elementExists('css', '.test-render div.render-image img');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderImageStyle
   */
  public function testImageStyle() {
    $this->drupalGet('/bamboo-twig-render');

    $this->assertSession()->elementExists('css', '.test-render div.render-image-style-uri');
    $this->assertElementContains('.test-render div.render-image-style-uri', 'files/styles/thumbnail/public/antistatique.jpg');

    $this->assertSession()->elementExists('css', '.test-render div.render-image-style-uri-preprocess');
    $this->assertElementContains('.test-render div.render-image-style-uri-preprocess', 'files/styles/thumbnail/public/antistatique.jpg');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderField
   * @group kevintest
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-render');

    // Entity articles (nodes) title field.
    $this->assertElementContains('.test-render div.render-field-title-node-1', '<span>News N°1</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-2', '<span>News N°2</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-3', '<span>News N°3</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-4', '<span>Article N°4</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-5', '<span>Article N°5</span>');

    // Entity tags (taxonomy) name field.
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-1', '<div>Tag N°1</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-2', '<div>Tag N°2</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-3', '<div>Tag N°3</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-4', '<div>Mot clé N°4</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-5', '<div>Mot clé N°5</div>');

    // Entity articles (node) tags reference field.
    $this->assertElementContains('.test-render div.render-field-reference-node-1', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-field-reference-node-2', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-field-reference-node-3', 'Tag N°3');
    $this->assertElementContains('.test-render div.render-field-reference-node-4', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-field-reference-node-5', 'Tag N°5');

    // Entity file uri.
    $this->assertElementContains('.test-render div.render-field-file', $this->file->filename->value);

    // Entity user username.
    $this->assertElementContains('.test-render div.render-field-user', 'admin');

    $this->drupalGet('/fr/bamboo-twig-render');

    // Entity articles (nodes) title field - French.
    $this->assertElementContains('.test-render div.render-field-title-node-1', '<span>News N°1</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-2', '<span>News N°2</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-3', '<span>News N°3</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-4', '<span>Article N°4</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-5', '<span>Article N°5</span>');

    // Entity tags (taxonomy) name field - French.
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-1', '<div>Tag N°1</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-2', '<div>Tag N°2</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-3', '<div>Tag N°3</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-4', '<div>Mot clé N°4</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-5', '<div>Mot clé N°5</div>');

    // Entity articles (node) tags reference field - French.
    $this->assertElementContains('.test-render div.render-field-reference-node-1', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-field-reference-node-2', 'Mot clé N°2');
    $this->assertElementContains('.test-render div.render-field-reference-node-3', 'Mot clé N°3');
    $this->assertElementContains('.test-render div.render-field-reference-node-4', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-field-reference-node-5', 'Mot clé N°5');

    $this->drupalGet('/de/bamboo-twig-render');

    // Entity articles (nodes) title field - German.
    $this->assertElementContains('.test-render div.render-field-title-node-1', '<span>News N°1</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-2', '<span>News N°2</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-3', '<span>News N°3</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-4', '<span>Article N°4</span>');
    $this->assertElementContains('.test-render div.render-field-title-node-5', '<span>Article N°5</span>');

    // Entity tags (taxonomy) name field - German.
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-1', '<div>Tag N°1</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-2', '<div>Tag N°2</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-3', '<div>Tag N°3</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-4', '<div>Mot clé N°4</div>');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term-5', '<div>Mot clé N°5</div>');

    // Entity articles (node) tags reference field - German.
    $this->assertElementContains('.test-render div.render-field-reference-node-1', 'Mot clé N°4');
    $this->assertElementContains('.test-render div.render-field-reference-node-2', 'Tag N°2');
    $this->assertElementContains('.test-render div.render-field-reference-node-3', 'Stichworte N°3');
    $this->assertElementContains('.test-render div.render-field-reference-node-4', 'Tag N°1');
    $this->assertElementContains('.test-render div.render-field-reference-node-5', 'Tag N°5');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderMenu
   */
  public function testMenu() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-menu-no-access');
    $this->assertSession()->elementNotExists('css', '.test-render div.render-menu-no-access ul');
    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-render');
    $this->assertSession()->elementExists('css', '.test-render div.render-menu-all');
    $this->assertElementCount('ul', 9, '.test-render div.render-menu-all');
    $this->assertElementCount('li', 25, '.test-render div.render-menu-all');
    $this->assertSession()->elementExists('css', '.test-render div.render-menu-level');
    $this->assertElementCount('ul', 8, '.test-render div.render-menu-level');
    $this->assertElementCount('li', 24, '.test-render div.render-menu-level');
    $this->assertSession()->elementExists('css', '.test-render div.render-menu-depth');
    $this->assertElementCount('ul', 2, '.test-render div.render-menu-depth');
    $this->assertElementCount('li', 3, '.test-render div.render-menu-depth');
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
