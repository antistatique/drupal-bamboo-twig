<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Loaders twig filters and functions.
 *
 * @group bamboo_twig
 */
class BambooTwigLoaderTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_loaders',
    'bamboo_twig_test',
    'node',
    'block',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create an article content type that we will use for testing.
    $this->drupalCreateContentType(['type' => 'article', 'name' => 'Article']);

    // Create a page content type that we will use for testing.
    $this->drupalCreateContentType(['type' => 'page', 'name' => 'Basic page']);

    // Create an article node that we will use for testing.
    $this->article = $this->drupalCreateNode([
      'title' => 'Hello, world!',
      'type' => 'article',
    ]);
    $this->article->save();

    // Create an article node that we will use for testing.
    $this->page = $this->drupalCreateNode([
      'title' => 'Foo Bar',
      'type' => 'page',
    ]);
    $this->page->save();

    // Create a user for tests.
    $this->admin_user = $this->drupalCreateUser([
      'access content',
      'administer content types',
      'bypass node access',
      'administer site configuration',
      'view the administration theme',
      'administer menu',
      'access administration pages',
    ]);

    $this->container->get('router.builder')->rebuild();
  }

  /**
   * @covers Drupal\bamboo_twig_loaders\TwigExtension\Loader::loadBlock
   */
  public function testBlock() {
    $this->drupalGet('/bamboo-twig-loaders');

    $this->assertElementPresent('.test-loaders div.loader-block');
    $this->assertElementContains('.test-loaders div.loader-block', '<span>Powered by <a href="https://www.drupal.org">Drupal</a></span>');
  }

  /**
   * @covers Drupal\bamboo_twig_loaders\TwigExtension\Loader::loadRegion
   */
  public function testRegion() {
    $this->drupalGet('/bamboo-twig-loaders');

    $this->assertElementPresent('.test-loaders div.loader-region');
    $this->assertElementPresent('.test-loaders div.loader-region #block-stark-login');
  }

  /**
   * @covers Drupal\bamboo_twig_loaders\TwigExtension\Loader::loadEntity
   */
  public function testEntity() {
    $this->drupalGet('/bamboo-twig-loaders');

    // Entity article full loaded.
    $this->assertElementPresent('.test-loaders div.loader-entity');
    $this->assertElementPresent('.test-loaders div.loader-entity h2 a');
    $this->assertElementContains('.test-loaders div.loader-entity h2 a', 'Hello, world!');
    $this->assertLinkLabelExist('Hello, world!');
    $this->assertLinkUrlExist('/node/' . $this->article->id());
    $this->assertElementPresent('.test-loaders div.loader-entity footer');
    $this->assertElementContains('.test-loaders div.loader-entity footer', 'Submitted by');

    // Entity page teaser loaded.
    $this->assertElementPresent('.test-loaders div.loader-entity-teaser');
    $this->assertElementPresent('.test-loaders div.loader-entity-teaser h2 a');
    $this->assertElementContains('.test-loaders div.loader-entity-teaser h2 a', 'Foo Bar');
    $this->assertLinkLabelExist('Foo Bar');
    $this->assertLinkUrlExist('/node/' . $this->page->id());
    $this->assertElementPresent('.test-loaders div.loader-entity-teaser footer');
    $this->assertElementContains('.test-loaders div.loader-entity-teaser footer', 'Submitted by');
    $this->assertLinkLabelExist('Read more');
  }

  /**
   * @covers Drupal\bamboo_twig_loaders\TwigExtension\Loader::loadField
   */
  public function testField() {
    $this->drupalGet('/bamboo-twig-loaders');

    $this->assertElementPresent('.test-loaders div.loader-field');
    $this->assertElementContains('.test-loaders div.loader-field', 'Hello, world!');
  }

  /**
   * @covers Drupal\bamboo_twig_loaders\TwigExtension\Loader::loadMenu
   */
  public function testMenu() {
    $this->drupalGet('/bamboo-twig-loaders');
    $this->assertElementPresent('.test-loaders div.loader-menu-no-access');
    $this->assertElementNotPresent('.test-loaders div.loader-menu-no-access ul');

    $this->drupalLogin($this->admin_user);
    $this->drupalGet('/bamboo-twig-loaders');
    $this->assertElementPresent('.test-loaders div.loader-menu-all');
    $this->assertElementCount('ul', 9, '.test-loaders div.loader-menu-all');
    $this->assertElementCount('li', 24, '.test-loaders div.loader-menu-all');

    $this->assertElementPresent('.test-loaders div.loader-menu-level');
    $this->assertElementCount('ul', 8, '.test-loaders div.loader-menu-level');
    $this->assertElementCount('li', 23, '.test-loaders div.loader-menu-level');

    $this->assertElementPresent('.test-loaders div.loader-menu-depth');
    $this->assertElementCount('ul', 2, '.test-loaders div.loader-menu-depth');
    $this->assertElementCount('li', 3, '.test-loaders div.loader-menu-depth');
  }

  /**
   * @covers Drupal\bamboo_twig_loaders\TwigExtension\Loader::loadForm
   */
  public function testForm() {
    $this->drupalGet('/bamboo-twig-loaders');
    $this->assertElementPresent('.test-loaders div.loader-form');
    $this->assertElementPresent('.test-loaders div.loader-form form.system-cron-settings');
  }

}
