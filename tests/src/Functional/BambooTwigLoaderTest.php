<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Tests\taxonomy\Functional\TaxonomyTestTrait;
use Drupal\Core\StreamWrapper\PublicStream;

/**
 * Tests Loaders twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_loader
 */
class BambooTwigLoaderTest extends BambooTwigTestBase {
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
    'file',
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
    $this->admin_user = $this->drupalCreateUser();

    // Create a taxonomy term for tests.
    $this->vocabulary = $this->createVocabulary();
    $this->term = $this->createTerm($this->vocabulary);

    // Create a file for tests.
    $this->file = $this->createFile();

    $this->container->get('router.builder')->rebuild();
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadCurrentUser
   */
  public function testCurrentUser() {
    $this->drupalGet('/bamboo-twig-loader');

    $this->assertElementPresent('.test-loaders div.loader-current-user');
    $this->assertElementContains('.test-loaders div.loader-current-user', '');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-loader');

    $this->assertElementPresent('.test-loaders div.loader-current-user');
    $this->assertElementContains('.test-loaders div.loader-current-user', $this->admin_user->getUsername());
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-loader');

    // Load entity article.
    $this->assertElementPresent('.test-loaders div.loader-entity-node');
    $this->assertElementContains('.test-loaders div.loader-entity-node', $this->article->getTitle());

    // Load entity taxonomy term.
    $this->assertElementPresent('.test-loaders div.loader-entity-taxonomy-term');
    $this->assertElementContains('.test-loaders div.loader-entity-taxonomy-term', $this->term->getName());

    // Load entity file.
    $this->assertElementPresent('.test-loaders div.loader-entity-file');
    $this->assertElementContains('.test-loaders div.loader-entity-file', $this->file->filename->value);

    // Load entity user.
    $this->assertElementPresent('.test-loaders div.loader-entity-user');
    $this->assertElementContains('.test-loaders div.loader-entity-user', 'admin');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadField
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-loader');

    // Load field article.
    $this->assertElementPresent('.test-loaders div.loader-field-node');
    $this->assertElementContains('.test-loaders div.loader-field-node', $this->article->getTitle());

    // Load field taxonomy term.
    $this->assertElementPresent('.test-loaders div.loader-field-taxonomy-term');
    $this->assertElementContains('.test-loaders div.loader-field-taxonomy-term', $this->term->getName());

    // Load field file.
    $this->assertElementPresent('.test-loaders div.loader-field-file');
    $this->assertElementContains('.test-loaders div.loader-field-file', $this->file->filename->value);

    // Load field user.
    $this->assertElementPresent('.test-loaders div.loader-field-user');
    $this->assertElementContains('.test-loaders div.loader-field-user', 'admin');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Loader::loadImage
   */
  public function testImage() {
    $this->drupalGet('/bamboo-twig-loader');
    // Load an image using uri public://antistatique.png.
    $this->assertElementPresent('.test-loaders div.loader-image-uri');
    $this->assertElementContains('.test-loaders div.loader-image-uri', $this->file->getFileUri());

    // Load an image using uri public://antistatique.png.
    $this->assertElementPresent('.test-loaders div.loader-image-path');
    $this->assertElementContains('.test-loaders div.loader-image-path', drupal_get_path('module', 'bamboo_twig_test') . '/files/antistatique.png');
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
    file_unmanaged_copy(drupal_get_path('module', 'bamboo_twig_test') . '/files/antistatique.png', PublicStream::basePath());
    $file = $fileStorage->create([
      'uri' => 'public://antistatique.png',
      'status' => FILE_STATUS_PERMANENT,
    ]);
    $file->save();
    return $file;
  }

}
