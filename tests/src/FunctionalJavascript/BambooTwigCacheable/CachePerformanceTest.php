<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigCacheable;

use Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata;
use Drupal\Core\Cache\Cache;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests cache performances (Gander) of Cacheable twig functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_gander
 */
#[CoversClass(BubbleMetadata::class)]
#[CoversMethod(BubbleMetadata::class, 'attachCacheableMetadata')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_gander')]
#[RunTestsInSeparateProcesses]
class CachePerformanceTest extends CacheablePerformanceTestBase {

  /**
   * Attached cache tags must invalidate the Dynamic Page Cache.
   */
  public function testAttachTags(): void {
    $this->warmUpCacheable('tags');

    // Invalidating an unrelated cache tag must keep the page cached.
    Cache::invalidateTags(['bamboo_twig_unrelated']);
    $unrelated = $this->collectCacheablePerformanceData('tags');
    $this->assertSame(0, $unrelated->getCacheSetCount(), 'Invalidating an unrelated cache tag must not invalidate the page.');

    // Invalidating the attached cache tag must re-build the page.
    Cache::invalidateTags(['bamboo_twig_performance']);
    $invalidated = $this->collectCacheablePerformanceData('tags');
    $this->assertGreaterThan(0, $invalidated->getCacheSetCount(), 'Invalidating the attached cache tag must invalidate the page.');

    $warm = $this->collectCacheablePerformanceData('tags');
    $this->assertSame(0, $warm->getCacheSetCount(), 'A re-built page must be cached again.');
  }

  /**
   * An attached max-age of 0 must prevent the Dynamic Page Cache.
   */
  public function testAttachMaxAge(): void {
    $this->warmUpCacheable('none');
    $this->warmUpCacheable('max_age');

    $cached = $this->collectCacheablePerformanceData('none');
    $uncached = $this->collectCacheablePerformanceData('max_age');

    // A page served from the Dynamic Page Cache is cheaper than a re-built one.
    $this->assertGreaterThan($cached->getCacheGetCount(), $uncached->getCacheGetCount(), 'An attached max-age of 0 must re-build the page on every request.');
    $this->assertSame(0, $uncached->getCacheSetCount(), 'An attached max-age of 0 must never write the page in the Dynamic Page Cache.');
  }

  /**
   * Attached cache contexts must vary the Dynamic Page Cache.
   */
  public function testAttachContexts(): void {
    $this->warmUpCacheable('none', ['bamboo' => 1]);
    $this->warmUpCacheable('contexts', ['bamboo' => 1]);

    // Without cache context, the page does not vary by the query argument,
    // only URL-based caches (eg. router) write a new entry.
    $none = $this->collectCacheablePerformanceData('none', ['bamboo' => 2]);

    // With the cache context, the same value must be served from caches.
    $same = $this->collectCacheablePerformanceData('contexts', ['bamboo' => 1]);
    $this->assertSame(0, $same->getCacheSetCount(), 'The same cache context value must be served from the Dynamic Page Cache.');

    // With the cache context, a new value is cached as a new variation.
    $other = $this->collectCacheablePerformanceData('contexts', ['bamboo' => 2]);
    $this->assertGreaterThan($none->getCacheSetCount(), $other->getCacheSetCount(), 'A new cache context value must be cached as a new variation.');
  }

}
