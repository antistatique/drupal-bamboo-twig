<?php

namespace Drupal\bamboo_twig_test\Controller\Performance;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns renderer-responses for performance testing of Cacheable functions.
 */
class CacheablePerformanceTestController extends ControllerBase {

  /**
   * Performance page attaching cacheable metadata.
   *
   * The page itself is cacheable, so the Dynamic Page Cache is only altered
   * by the attached cacheable metadata.
   *
   * @param string $metadata
   *   The cacheable metadata to attach, eg. "none", "tags", "max_age", ...
   */
  public function attach(string $metadata) {
    return [
      '#variables' => [
        'metadata' => $metadata,
      ],
      '#theme' => 'bamboo_twig_test_performance_cacheable',
    ];
  }

}
