<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests Cacheable twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 */
#[CoversClass(BubbleMetadata::class)]
#[CoversMethod(BubbleMetadata::class, 'attachCacheableMetadata')]
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
   * Tests attachCacheableMetadata() propagates cache contexts to the response.
   */
  public function testCacheableContexts() {
    $this->drupalGet('/bamboo-twig-cacheable');
    $this->assertSession()->elementExists('css', '.test-cacheable');
    $this->assertSession()->elementExists('css', '.test-cacheable div.cacheable-contexts');
    $this->assertSession()->responseHeaderContains('X-Drupal-Cache-Contexts', 'ip');
  }

  /**
   * Tests attachCacheableMetadata() propagates cache tags to the response.
   */
  public function testCacheableTags() {
    $this->drupalGet('/bamboo-twig-cacheable');

    $this->assertSession()->elementExists('css', '.test-cacheable div.cacheable-tags');
    $this->assertSession()->responseHeaderContains('X-Drupal-Cache-Tags', 'entity.kitten.1');
  }

  /**
   * Tests attachCacheableMetadata() propagates max-age to the response.
   */
  public function testCacheableMaxAge() {
    $this->drupalGet('/bamboo-twig-cacheable');

    $this->assertSession()->elementExists('css', '.test-cacheable div.cacheable-max-age');
    $this->assertSession()->responseHeaderContains('X-Drupal-Cache-Max-Age', 12);
  }

}
