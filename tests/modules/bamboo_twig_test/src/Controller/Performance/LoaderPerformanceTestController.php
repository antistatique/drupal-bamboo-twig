<?php

namespace Drupal\bamboo_twig_test\Controller\Performance;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns renderer-responses for performance testing of Loaders Twig functions.
 */
class LoaderPerformanceTestController extends ControllerBase {

  /**
   * Performance page loading the same thing many times.
   *
   * @param string $function
   *   The Loader function to use, eg. "entity", "field", "image", ...
   * @param int $iterations
   *   The number of time the same thing will be loaded by the template.
   */
  public function load(string $function, int $iterations) {
    return [
      '#variables' => [
        'function' => $function,
        'iterations' => $iterations,
      ],
      '#theme' => 'bamboo_twig_test_performance_loader',
      // Never cache the page so every request renders the template.
      '#cache' => ['max-age' => 0],
    ];
  }

}
