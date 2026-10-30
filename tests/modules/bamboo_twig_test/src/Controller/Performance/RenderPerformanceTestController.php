<?php

namespace Drupal\bamboo_twig_test\Controller\Performance;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns renderer-responses for performance testing of Render Twig functions.
 */
class RenderPerformanceTestController extends ControllerBase {

  /**
   * Performance page rendering the same thing many times.
   *
   * @param string $function
   *   The Render function to use, eg. "entity", "field", "block", ...
   * @param int $iterations
   *   The number of time the same thing will be rendered by the template.
   */
  public function render(string $function, int $iterations) {
    return [
      '#variables' => [
        'function' => $function,
        'iterations' => $iterations,
      ],
      '#theme' => 'bamboo_twig_test_performance_render',
      // Never cache the page so every request renders the template.
      '#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Cacheable performance page rendering something once.
   *
   * The page itself is cacheable, so the Page Cache and the Dynamic Page Cache
   * are only altered by the rendered thing.
   *
   * @param string $function
   *   The Render function to use, eg. "entity", "field", "block", ...
   */
  public function page(string $function) {
    return [
      '#variables' => [
        'function' => $function,
        'iterations' => 1,
      ],
      '#theme' => 'bamboo_twig_test_performance_render',
    ];
  }

}
