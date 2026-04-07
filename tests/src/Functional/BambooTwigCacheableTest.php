<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests Cacheable twig filters and functions.
 */
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_cacheable')]
#[Group('bamboo_twig_cacheable_functional')]
#[RunTestsInSeparateProcesses]
class BambooTwigCacheableTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_cacheable',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata::attachCacheableMetadata
   */
  public function testCacheableContexts() {
    $this->drupalGet('/bamboo-twig-cacheable');
    $this->assertSession()->elementExists('css', '.test-cacheable');
    $this->assertSession()->elementExists('css', '.test-cacheable div.cacheable-contexts');
    $this->assertSession()->responseHeaderContains('X-Drupal-Cache-Contexts', 'ip');
  }

  /**
   * @covers Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata::attachCacheableMetadata
   */
  public function testCacheableTags() {
    $this->drupalGet('/bamboo-twig-cacheable');

    $this->assertSession()->elementExists('css', '.test-cacheable div.cacheable-tags');
    $this->assertSession()->responseHeaderContains('X-Drupal-Cache-Tags', 'entity.kitten.1');
  }

  /**
   * @covers Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata::attachCacheableMetadata
   */
  public function testCacheableMaxAge() {
    $this->drupalGet('/bamboo-twig-cacheable');

    $this->assertSession()->elementExists('css', '.test-cacheable div.cacheable-max-age');
    $this->assertSession()->responseHeaderContains('X-Drupal-Cache-Max-Age', 12);
  }

}
