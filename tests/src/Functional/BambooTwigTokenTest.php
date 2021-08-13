<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Token twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_token
 */
class BambooTwigTokenTest extends BambooTwigTestBase {
  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'locale',
    'language',
    'node',
    'taxonomy',
    'bamboo_twig',
    'bamboo_twig_token',
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

    $this->container->get('router.builder')->rebuild();
  }

  /**
   * @covers Drupal\bamboo_twig_token\TwigExtension\Token::substituteToken
   */
  public function testSubstituteToken() {
    $this->drupalGet('/bamboo-twig-token');
    $this->assertElementContains('.test-token div.token-site', 'Drupal');
    $this->assertElementContains('.test-token div.token-node-1', 'News N°1');
    $this->assertElementContains('.test-token div.token-node-2', 'News N°2');
    $this->assertElementContains('.test-token div.token-node-3', 'News N°3');
    $this->assertElementContains('.test-token div.token-node-4', 'Article N°4');
    $this->assertElementContains('.test-token div.token-node-5', 'News N°5');

    $this->drupalGet('/fr/bamboo-twig-token');
    $this->assertElementContains('.test-token div.token-site', 'Drupal');
    $this->assertElementContains('.test-token div.token-node-1', 'News N°1');
    $this->assertElementContains('.test-token div.token-node-2', 'Article N°2');
    $this->assertElementContains('.test-token div.token-node-3', 'Article N°3');
    $this->assertElementContains('.test-token div.token-node-4', 'Article N°4');
    $this->assertElementContains('.test-token div.token-node-5', 'Article N°5');

    $this->drupalGet('/de/bamboo-twig-token');
    $this->assertElementContains('.test-token div.token-site', 'Drupal');
    $this->assertElementContains('.test-token div.token-node-1', 'News N°1');
    $this->assertElementContains('.test-token div.token-node-2', 'News N°2');
    $this->assertElementContains('.test-token div.token-node-3', 'Artikel N°3');
    $this->assertElementContains('.test-token div.token-node-4', 'Article N°4');
    $this->assertElementContains('.test-token div.token-node-5', 'News N°5');
  }

}
