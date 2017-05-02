<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Token twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_token
 */
class BambooTwigTokenTest extends BambooTwigTestBase {
  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_token',
    'bamboo_twig_loader',
    'bamboo_twig_test',
    'node',
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

    $this->container->get('router.builder')->rebuild();
  }

  /**
   * @covers Drupal\bamboo_twig_token\TwigExtension\Token::substituteToken
   */
  public function testSubstituteToken() {
    $this->drupalGet('/bamboo-twig-token');
    $this->assertElementPresent('.test-token div.token-site');
    $this->assertElementContains('.test-token div.token-site', 'Drupal');

    $this->assertElementPresent('.test-token div.token-node');
    $this->assertElementContains('.test-token div.token-node', 'Hello, world!');
  }

}
