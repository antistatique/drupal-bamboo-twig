<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\FileInterface;

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
  public function setUp(): void {

    parent::setUp();

    $this->setUpLanguages();
    $this->setUpTags();
    $this->setUpArticles();

    // Create a user for tests.
    $this->admin_user = $this->drupalCreateUser();

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
    $this->assertElementContains('.test-loaders div.loader-current-user', '');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-loader');

    $this->assertSession()->elementExists('css', '.test-loaders div.loader-current-user');
    $this->assertElementContains('.test-loaders div.loader-current-user', $this->admin_user->getAccountName());
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-loader');

    // Load entity article (node).
    $this->assertElementContains('.test-loaders div.loader-entity-node-1', 'News N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-node-2', 'News N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-node-3', 'News N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-node-4', 'Article N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-node-5', 'News N°5');

    // Load entity tag (taxonomy).
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-1', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-4', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-5', 'Tag N°5');

    // Load entity article (node) referenced field.
    // Referenced field will always display the entity in its own original lang.
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-5', 'Mot clé N°5');

    // Load entity file.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-entity-file');
    $this->assertElementContains('.test-loaders div.loader-entity-file', 'antistatique.jpg');

    // Load entity user.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-entity-user');
    $this->assertElementContains('.test-loaders div.loader-entity-user', 'admin');

    $this->drupalGet('/fr/bamboo-twig-loader');

    // Load entity article (node) - French.
    $this->assertElementContains('.test-loaders div.loader-entity-node-1', 'News N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-node-2', 'Article N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-node-3', 'Article N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-node-4', 'Article N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-node-5', 'Article N°5');

    // Load entity tag (taxonomy) - French.
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-1', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-2', 'Mot clé N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-3', 'Mot clé N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-4', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-5', 'Mot clé N°5');

    // Load entity article (node) referenced field - French.
    // Referenced field will always display the entity in its own original lang.
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-5', 'Mot clé N°5');

    $this->drupalGet('/de/bamboo-twig-loader');

    // Load entity article (node) - German.
    $this->assertElementContains('.test-loaders div.loader-entity-node-1', 'News N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-node-2', 'News N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-node-3', 'Artikel N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-node-4', 'Article N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-node-5', 'News N°5');

    // Load entity tag (taxonomy) - German.
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-1', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-3', 'Stichworte N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-4', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term-5', 'Tag N°5');

    // Load entity article (node) referenced field - German.
    // Referenced field will always display the entity in its own original lang.
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-1', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-4', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-entity-reference-field-5', 'Mot clé N°5');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadField
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-loader');

    // Load field article (node).
    $this->assertElementContains('.test-loaders div.loader-field-node-1', 'News N°1');
    $this->assertElementContains('.test-loaders div.loader-field-node-2', 'News N°2');
    $this->assertElementContains('.test-loaders div.loader-field-node-3', 'News N°3');
    $this->assertElementContains('.test-loaders div.loader-field-node-4', 'Article N°4');
    $this->assertElementContains('.test-loaders div.loader-field-node-5', 'News N°5');

    // Load field tag (taxonomy).
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-1', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-5', 'Tag N°5');

    // Load entity article (node) referenced field.
    // Referenced field will always display the entity in its own original lang.
    $this->assertElementContains('.test-loaders div.loader-field-reference-1', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-field-reference-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-field-reference-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-field-reference-4', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-field-reference-5', 'Mot clé N°5');

    // Load field file.
    $this->assertElementContains('.test-loaders div.loader-field-file', $this->file->filename->value);

    // Load field user.
    $this->assertElementContains('.test-loaders div.loader-field-user', 'admin');

    $this->drupalGet('/fr/bamboo-twig-loader');

    // Load field article (node) - French.
    $this->assertElementContains('.test-loaders div.loader-field-node-1', 'News N°1');
    $this->assertElementContains('.test-loaders div.loader-field-node-2', 'Article N°2');
    $this->assertElementContains('.test-loaders div.loader-field-node-3', 'Article N°3');
    $this->assertElementContains('.test-loaders div.loader-field-node-4', 'Article N°4');
    $this->assertElementContains('.test-loaders div.loader-field-node-5', 'Article N°5');

    // Load field tag (taxonomy) - French.
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-1', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-2', 'Mot clé N°2');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-3', 'Mot clé N°3');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-5', 'Mot clé N°5');

    // Load entity article (node) referenced field - French.
    // Referenced field will always display the entity in its own original lang.
    $this->assertElementContains('.test-loaders div.loader-field-reference-1', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-field-reference-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-field-reference-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-field-reference-4', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-field-reference-5', 'Mot clé N°5');

    $this->drupalGet('/de/bamboo-twig-loader');

    // Load field article (node) - German.
    $this->assertElementContains('.test-loaders div.loader-field-node-1', 'News N°1');
    $this->assertElementContains('.test-loaders div.loader-field-node-2', 'News N°2');
    $this->assertElementContains('.test-loaders div.loader-field-node-3', 'Artikel N°3');
    $this->assertElementContains('.test-loaders div.loader-field-node-4', 'Article N°4');
    $this->assertElementContains('.test-loaders div.loader-field-node-5', 'News N°5');

    // Load field tag (taxonomy) - German.
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-1', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-3', 'Stichworte N°3');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-4', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term-5', 'Tag N°5');

    // Load entity article (node) referenced field - German.
    // Referenced field will always display the entity in its own original lang.
    $this->assertElementContains('.test-loaders div.loader-field-reference-1', 'Mot clé N°4');
    $this->assertElementContains('.test-loaders div.loader-field-reference-2', 'Tag N°2');
    $this->assertElementContains('.test-loaders div.loader-field-reference-3', 'Tag N°3');
    $this->assertElementContains('.test-loaders div.loader-field-reference-4', 'Tag N°1');
    $this->assertElementContains('.test-loaders div.loader-field-reference-5', 'Mot clé N°5');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadImage
   */
  public function testImage() {
    $this->drupalGet('/bamboo-twig-loader');
    // Load an image using uri public://antistatique.jpg.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-image-uri');
    $this->assertElementContains('.test-loaders div.loader-image-uri', $this->file->getFileUri());

    // Load an image using uri public://antistatique.jpg.
    $this->assertSession()->elementExists('css', '.test-loaders div.loader-image-path');
    $this->assertElementContains('.test-loaders div.loader-image-path', 'bamboo_twig_test/files/antistatique.jpg');
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
