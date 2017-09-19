<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Tests\taxonomy\Functional\TaxonomyTestTrait;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * Tests Renders twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_render
 */
class BambooTwigRenderTest extends BambooTwigTestBase {
  use TaxonomyTestTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_loader',
    'bamboo_twig_test',
    'node',
    'user',
    'taxonomy',
    'image',
    'file',
    'system',
    'views'
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create an article content type that we will use for testing.
    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);

    // Create an article node that we will use for testing.
    $this->article = $this->drupalCreateNode([
      'title' => 'Hello, world!',
      'type' => 'article',
    ]);
    $this->article->save();

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

    // Create a taxonomy term for tests.
    $this->vocabulary = $this->createVocabulary();
    $this->term = $this->createTerm($this->vocabulary);

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
    $this->assertElementPresent('.test-render div.render-block-plugin');
    $this->assertElementContains('.test-render div.render-block-plugin', '<span>Powered by <a href="https://www.drupal.org">Drupal</a></span>');

    // Tests for Block Entity.
    $this->assertElementPresent('.test-render div.render-block-entity');
    $this->assertElementPresent('.test-render div.render-block-entity #block-stark-branding');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderRegion
   */
  public function testRegion() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertElementPresent('.test-render div.render-region');
    $this->assertElementPresent('.test-render div.render-region #block-stark-login');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-render');
    // Entity node (article) full loaded.
    $this->assertElementPresent('.test-render div.render-entity-node');
    $this->assertElementPresent('.test-render div.render-entity-node h2 a');
    $this->assertElementContains('.test-render div.render-entity-node h2 a', $this->article->getTitle());
    $this->assertElementPresent('.test-render div.render-entity-node footer');
    $this->assertElementContains('.test-render div.render-entity-node footer', 'Submitted by');
    // Entity node (article) teaser loaded.
    $this->assertElementPresent('.test-render div.render-entity-node-teaser');
    $this->assertElementPresent('.test-render div.render-entity-node-teaser h2 a');
    $this->assertElementContains('.test-render div.render-entity-node-teaser h2 a', $this->article->getTitle());
    $this->assertElementPresent('.test-render div.render-entity-node-teaser footer');
    $this->assertElementContains('.test-render div.render-entity-node-teaser footer', 'Submitted by');
    $this->assertElementContains('.test-render div.render-entity-node-teaser .links', 'Read more');

    // Entity taxonomy term (term) full loaded.
    $this->assertElementPresent('.test-render div.render-entity-taxonomy-term');
    $this->assertElementPresent('.test-render div.render-entity-taxonomy-term h2 a');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term h2 a', $this->term->getName());
    // Entity taxonomy term (term) link loaded.
    $this->assertElementPresent('.test-render div.render-entity-taxonomy-term-link');
    $this->assertElementPresent('.test-render div.render-entity-taxonomy-term-link h2 a');
    $this->assertElementContains('.test-render div.render-entity-taxonomy-term-link h2 a', $this->term->getName());

    // Entity user full loaded.
    $this->assertElementPresent('.test-render div.render-entity-user');
    $this->assertElementContains('.test-render div.render-entity-user', 'Member for');
    // Entity user compact loaded.
    $this->assertElementPresent('.test-render div.render-entity-user-compact');
    $this->assertElementContains('.test-render div.render-entity-user-compact', 'Member for');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderImage
   */
  public function testImage() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertElementPresent('.test-render div.render-image');
    $this->assertElementPresent('.test-render div.render-image img');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderImageStyle
   */
  public function testImageStyle() {
    $this->drupalGet('/bamboo-twig-render');

    $this->assertElementPresent('.test-render div.render-image-style-uri');
    $this->assertElementContains('.test-render div.render-image-style-uri', 'files/styles/thumbnail/public/antistatique.jpg');

    $this->assertElementPresent('.test-render div.render-image-style-uri-preprocess');
    $this->assertElementContains('.test-render div.render-image-style-uri-preprocess', 'files/styles/thumbnail/public/antistatique.jpg');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderField
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-render');

    // Entity node (article) title.
    $this->assertElementPresent('.test-render div.render-field-node');
    $this->assertElementContains('.test-render div.render-field-node', '<span>Hello, world!</span>');

    // Entity taxonomy term (term) name.
    $this->assertElementPresent('.test-render div.render-field-taxonomy-term');
    $this->assertElementContains('.test-render div.render-field-taxonomy-term', '<div>' . $this->term->getName() . '</div>');

    // Entity file uri.
    $this->assertElementPresent('.test-render div.render-field-file');
    $this->assertElementContains('.test-render div.render-field-file', $this->file->filename->value);

    // Entity user username.
    $this->assertElementPresent('.test-render div.render-field-user');
    $this->assertElementContains('.test-render div.render-field-user', 'admin');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderMenu
   */
  public function testMenu() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertElementPresent('.test-render div.render-menu-no-access');
    $this->assertElementNotPresent('.test-render div.render-menu-no-access ul');
    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-render');
    $this->assertElementPresent('.test-render div.render-menu-all');
    $this->assertElementCount('ul', 9, '.test-render div.render-menu-all');
    $this->assertElementCount('li', 25, '.test-render div.render-menu-all');
    $this->assertElementPresent('.test-render div.render-menu-level');
    $this->assertElementCount('ul', 8, '.test-render div.render-menu-level');
    $this->assertElementCount('li', 24, '.test-render div.render-menu-level');
    $this->assertElementPresent('.test-render div.render-menu-depth');
    $this->assertElementCount('ul', 2, '.test-render div.render-menu-depth');
    $this->assertElementCount('li', 3, '.test-render div.render-menu-depth');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderForm
   */
  public function testForm() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertElementPresent('.test-render div.render-form');
    $this->assertElementPresent('.test-render div.render-form form.system-cron-settings');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::getFunctions
   */
  public function testViews() {
    $this->drupalGet('/bamboo-twig-render');
    $this->assertElementPresent('.test-render div.render-views');
    $this->assertElementPresent('.test-render div.render-views .views-element-container');
  }

  /**
   * Creates and gets test image file.
   *
   * @return \Drupal\file\FileInterface
   *   File object.
   */
  protected function createFile() {
    /** @var \Drupal\Component\PhpStorage\FileStorage $fileStorage */
    $fileStorage = $this->container->get('entity_type.manager')->getStorage('file');
    file_unmanaged_copy(drupal_get_path('module', 'bamboo_twig_test') . '/files/antistatique.jpg', PublicStream::basePath());
    $file = $fileStorage->create([
      'uri' => 'public://antistatique.jpg',
      'status' => FILE_STATUS_PERMANENT,
    ]);
    $file->save();
    return $file;
  }

}
