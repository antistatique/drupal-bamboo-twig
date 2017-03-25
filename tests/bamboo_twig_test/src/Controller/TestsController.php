<?php

namespace Drupal\bamboo_twig_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * TestsController.
 */
class TestsController extends ControllerBase {

  /**
   * Loaders page.
   */
  public function loaders() {
    return ['#theme' => 'bamboo_twig_test_loaders'];
  }

}
