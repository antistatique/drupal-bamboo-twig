<?php

namespace Drupal\bamboo_twig_test\Controller\Performance;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns renderer-responses for performance testing of Loaders Twig functions.
 */
class LoaderPerformanceTestController extends ControllerBase {

  /**
   * Performance page loading the same entity many times.
   *
   * @param int $iterations
   *   The number of time the same entity will be loaded by the template.
   */
  public function loadEntity(int $iterations) {
    return [
      '#variables' => [
        'iterations' => $iterations,
      ],
      '#theme' => 'bamboo_twig_test_performance_loader',
      // Never cache the page so every request renders the template.
      '#cache' => ['max-age' => 0],
    ];
  }

}
