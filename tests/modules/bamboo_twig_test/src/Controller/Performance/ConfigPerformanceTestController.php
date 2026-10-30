<?php

namespace Drupal\bamboo_twig_test\Controller\Performance;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns renderer-responses for performance testing of Config functions.
 */
class ConfigPerformanceTestController extends ControllerBase {

  /**
   * Performance page calling the same Config function many times.
   *
   * @param string $function
   *   The Config function to use.
   * @param int $iterations
   *   The number of time the same function will be called by the template.
   */
  public function call(string $function, int $iterations) {
    return [
      '#variables' => [
        'function' => $function,
        'iterations' => $iterations,
      ],
      '#theme' => 'bamboo_twig_test_performance_config',
      // Never cache the page so every request renders the template.
      '#cache' => ['max-age' => 0],
    ];
  }

}
