<?php

namespace Drupal\Tests\bamboo_twig\FunctionalJavascript\BambooTwigCacheable;

use Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests database performances (Gander) of Cacheable twig functions.
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
class DatabasePerformanceTest extends CacheablePerformanceTestBase {

  /**
   * Attaching cache tags or contexts must not add any database query.
   */
  public function testAttachQueries(): void {
    $this->warmUpCacheable('none');
    $this->warmUpCacheable('tags');
    $this->warmUpCacheable('contexts');

    $none = $this->collectCacheablePerformanceData('none');
    $tags = $this->collectCacheablePerformanceData('tags');
    $contexts = $this->collectCacheablePerformanceData('contexts');

    // Served from the Dynamic Page Cache, every page must cost the same.
    $this->assertSame($none->getQueryCount(), $tags->getQueryCount(), 'Attached cache tags must not trigger additional database queries.');
    $this->assertSame($none->getQueryCount(), $contexts->getQueryCount(), 'Attached cache contexts must not trigger additional database queries.');
  }

}
