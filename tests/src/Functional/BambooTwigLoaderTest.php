<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\FileInterface;
use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;

/**
 * Tests Loaders twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_loader
 */
class BambooTwigLoaderTest extends BambooTwigTestBase {
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
    'file',
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
    // Node module permissions.
    'access content overview',
    'administer content types',
    'administer nodes',
    'bypass node access',
    // Taxonomy module permissions.
    'administer taxonomy',
  ];

  /**
   * A user with administration access.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

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
   * A file used by this test.
   *
   * @var \Drupal\file\FileInterface
   */
  protected $file;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    $this->setUpLanguages();
    $this->setUpTags();
    $this->setUpArticles();
    $this->setupPages();

    // Create a user for tests.
    $this->adminUser = $this->drupalCreateUser(static::$adminUserPermissions);

    // Create a file for tests.
    $this->file = $this->createFile();

    $this->container->get('router.builder')->rebuild();
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadCurrentUser
   */
  public function testCurrentUser() {
    $this->drupalGet('/bamboo-twig-loader');

    $this->assertSession()->elementExists('css', '.test-loaders div.loader-current-user');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-current-user', '');

    $this->drupalLogin($this->adminUser);
    $this->drupalGet('/bamboo-twig-loader');

    $this->assertSession()->elementExists('css', '.test-loaders div.loader-current-user');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-current-user', $this->adminUser->getAccountName());
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-loader');

    // Load entity article (node).
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-3', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-5', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-loaders div.loader-entity-node-8');

    // Load entity tag (taxonomy).
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-5', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-loaders div.loader-entity-taxonomy-term-7');

    // Load entity article (node) referenced field.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-6', 'News N°6 - Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-7', 'Page N°7 -');
    $this->assertElementEmpty('.test-loaders div.loader-entity-reference-field-8');

    // Load entity file.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-entity-file');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-file', 'antistatique.jpg');

    // Load entity user.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-entity-user');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-user', 'admin');

    $this->drupalGet('/fr/bamboo-twig-loader');

    // Load entity article (node) - French.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-2', 'Article N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-3', 'Article N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-5', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-loaders div.loader-entity-node-8');

    // Load entity tag (taxonomy) - French.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-2', 'Mot clé N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-3', 'Mot clé N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-loaders div.loader-entity-taxonomy-term-7');

    // Load entity article (node) referenced field - French.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-6', 'News N°6 - Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-7', 'Page N°7 -');
    $this->assertElementEmpty('.test-loaders div.loader-entity-reference-field-8');

    $this->drupalGet('/de/bamboo-twig-loader');

    // Load entity article (node) - German.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-3', 'Artikel N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-5', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-loaders div.loader-entity-node-8');

    // Load entity tag (taxonomy) - German.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-3', 'Stichworte N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-5', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-loaders div.loader-entity-taxonomy-term-7');

    // Load entity article (node) referenced field - German.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-6', 'News N°6 - Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-entity-reference-field-7', 'Page N°7 -');
    $this->assertElementEmpty('.test-loaders div.loader-entity-reference-field-8');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntityRevision
   */
  public function testEntityRevision() {
    $this->drupalGet('/bamboo-twig-loader-revision');

    // Load entity article (node) revision.
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-node-revision-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-node-revision-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-node-revision-7', 'Revised News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-node-revision-8', 'Page N°7');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-node-revision-9', 'Revised Page N°7');
    $this->assertElementEmpty('.test-loaders-revision .loader-entity-node-revision-10');

    // Load entity tag (taxonomy) revision.
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-taxonomy-term-revision-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-taxonomy-term-revision-6', 'Tag N°6');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-taxonomy-term-revision-7', 'Revised Tag N°6');
    $this->assertElementEmpty('.test-loaders-revision .loader-entity-taxonomy-term-revision-8');

    // Load entity (node) referenced field.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-reference-field-1', 'News N°1 - Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-reference-field-6', 'News N°6 - Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-reference-field-7', 'Revised News N°6 - Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-reference-field-8', 'Page N°7 -');
    $this->assertSession()->elementContains('css', '.test-loaders-revision .loader-entity-reference-field-9', 'Revised Page N°7 -');
    $this->assertElementEmpty('.test-loaders-revision .loader-entity-reference-field-10');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntity
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntityRevision
   */
  public function testEntityFromRoutes() {
    // Accessing unpublished revision page required to be authenticated.
    $this->drupalLogin($this->adminUser);

    // Load entity page (node) from the current route.
    $this->drupalGet('/node/7');
    $this->assertSession()->elementContains('css', '.loader-current-node', 'Page N°7');
    $this->assertElementEmpty('.loader-current-node-revision');
    $this->assertSession()->elementContains('css', '.loader-field-current-node-title', 'Page N°7');

    // Load entity page (node revision) from the current route.
    $this->drupalGet('/node/7/revisions/9/view');
    $this->assertSession()->elementContains('css', '.loader-current-node', 'Page N°7');
    $this->assertSession()->elementContains('css', '.loader-current-node-revision', 'Revised Page N°7');
    $this->assertSession()->elementContains('css', '.loader-field-current-node-title', 'Page N°7');

    // Load entity article (node) from the current route.
    $this->drupalGet('/node/6');
    $this->assertSession()->elementContains('css', '.loader-current-node', 'News N°6');
    $this->assertElementEmpty('.loader-current-node-revision');
    $this->assertSession()->elementContains('css', '.loader-field-current-node-title', 'News N°6');
    $this->assertSession()->elementContains('css', '.loader-field-current-node-field-reference', 'Mot clé N°4');

    // Load entity article (node revision) from the current route.
    $this->drupalGet('/node/6/revisions/7/view');
    $this->assertSession()->elementContains('css', '.loader-current-node', 'News N°6');
    $this->assertSession()->elementContains('css', '.loader-current-node-revision', 'Revised News N°6');
    $this->assertSession()->elementContains('css', '.loader-field-current-node-title', 'News N°6');
    $this->assertSession()->elementContains('css', '.loader-field-current-node-field-reference', 'Mot clé N°4');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadField
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-loader');

    // Load field article (node).
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-3', 'News N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-5', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-loaders div.loader-field-node-8');

    // Load field tag (taxonomy).
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-5', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-loaders div.loader-field-taxonomy-term-7');

    // Load entity article (node) referenced field.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-6', 'Mot clé N°5');
    $this->assertElementEmpty('.test-loaders div.loader-field-reference-7');

    // Load field file.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-file', $this->file->filename->value);

    // Load field user.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-user', 'admin');

    $this->drupalGet('/fr/bamboo-twig-loader');

    // Load field article (node) - French.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-2', 'Article N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-3', 'Article N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-5', 'Article N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-loaders div.loader-field-node-8');

    // Load field tag (taxonomy) - French.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-2', 'Mot clé N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-3', 'Mot clé N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-loaders div.loader-field-taxonomy-term-7');

    // Load entity article (node) referenced field - French.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-6', 'Mot clé N°5');
    $this->assertElementEmpty('.test-loaders div.loader-field-reference-7');

    $this->drupalGet('/de/bamboo-twig-loader');

    // Load field article (node) - German.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-1', 'News N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-2', 'News N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-3', 'Artikel N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-4', 'Article N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-5', 'News N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-6', 'News N°6');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-node-7', 'Page N°7');
    $this->assertElementEmpty('.test-loaders div.loader-field-node-8');

    // Load field tag (taxonomy) - German.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-1', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-3', 'Stichworte N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-5', 'Tag N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-taxonomy-term-6', 'Tag N°6');
    $this->assertElementEmpty('.test-loaders div.loader-field-taxonomy-term-7');

    // Load entity article (node) referenced field - German.
    // Referenced field will always display the entity in its own original lang.
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-1', 'Mot clé N°4');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-2', 'Tag N°2');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-3', 'Tag N°3');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-4', 'Tag N°1');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-5', 'Mot clé N°5');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-field-reference-6', 'Mot clé N°5');
    $this->assertElementEmpty('.test-loaders div.loader-field-reference-7');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadImage
   */
  public function testImage() {
    $this->drupalGet('/bamboo-twig-loader');
    // Load an image using uri public://antistatique.jpg.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-image-uri');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-image-uri', $this->file->getFileUri());

    // Load an image using uri public://antistatique.jpg.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-image-path');
    $this->assertSession()->elementContains('css', '.test-loaders div.loader-image-path', 'bamboo_twig_test/files/antistatique.jpg');
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
